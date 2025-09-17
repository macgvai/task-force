<?php

namespace app\controllers;

use app\models\Category;
use app\models\File;
use app\models\Opinion;
use app\models\Reply;
use app\models\SearchModel;
use app\models\Status;
use app\models\Task;
use GuzzleHttp\Client;
use victor\logic\actions\CancelAction;
use victor\logic\actions\DenyAction;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\widgets\ActiveForm;

class TasksController extends SecureController
{
    public function actionIndex(){
        // Создаем модель для заданий
        $tasksModel = new Task();
        $tasksModel->load(Yii::$app->request->post());
        $queryTasks =  $tasksModel->getFilters(1);

        // Получаем все категории
        $categories = Category::find()->all();

        $tasks = $queryTasks->all();

        return $this->render('index', [
            'tasksModel' => $tasksModel,
            'tasks' => $tasks,
            'categories' => $categories
        ]);
    }

    public function actionView($id)
    {
        // Получаем задание по id
        $task = Task::findOne($id);

        // Получаем отклики на задание
        $replies = $task->getReplies()->all();

        $newReply = new Reply();

        $opinion = new Opinion();

        // Проверяем, существует ли задание
        if ($task === null) {
            throw new NotFoundHttpException('Задание не найдено.');
        }

        return $this->render('view', [
            'task' => $task, // Передаем задание в представление
            'replies' => $replies, // Передаем отклики
            'newReply' => $newReply,
            'opinion' => $opinion
        ]);
    }

    public function actionCreate()
    {
        $model = new Task();
        $category = ArrayHelper::map(Category::find()->all(), 'id', 'name');
        $model->client_id = Yii::$app->getUser()->id;

        if (!Yii::$app->session->has('task_uid')) {
            Yii::$app->session->set('task_uid', uniqid('upload'));
        }

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            $model->uid = Yii::$app->session->get('task_uid');
            $model->save();

            if ($model->id) {
                Yii::$app->session->remove('task_uid');
                return $this->redirect(['tasks/view', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
            'categories' => $category
        ]);
    }


    public function actionCancel($id)
    {
        /**
         * @var Task $task
         */
        $task = $this->findOrDie($id, Task::class);
        $task->goToNextStatus(new CancelAction);

        return $this->redirect(['tasks/view', 'id' => $task->id]);
    }

    public function actionUpload()
    {
        if (Yii::$app->request->isPost) {
            $model = new File();
            $model->file = UploadedFile::getInstanceByName('file');
            $model->task_uid = Yii::$app->session->get('task_uid');
            $model->user_id = Yii::$app->getUser()->id;


            $model->upload();

            return $this->asJson($model->getAttributes());
        }
    }


    public function actionDeny($id)
    {
        /**
         * @var Task $task
         */
        $task = $this->findOrDie($id, Task::class);
        $task->goToNextStatus(new DenyAction());

        $performer = $task->performer;
        $performer->increaseFailCount();

        return $this->redirect(['tasks/view', 'id' => $task->id]);
    }

    public function actionApprove()
    {
        $replId = Yii::$app->request->get('repl');
        $replStatus = Yii::$app->request->get('approve');

        $reply = Reply::findOne($replId);
        $task = $reply->task;

        $reply->is_approved = filter_var($replStatus, FILTER_VALIDATE_BOOLEAN);;
        $reply->save();

        $task->performer_id = $reply->user_id;
        $task->status_id = Status::STATUS_IN_PROGRESS;
        $task->save(false);

        return $this->redirect(['tasks/view', 'id' => $reply->task_id]);
    }

    public function actionSearchGeoPosition()
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            try {
                $client = new Client([
                    'verify' => __DIR__ . '/../cacert.pem' // Указываем путь к CA-файлу
                ]);

                $jsonData = Yii::$app->request->getRawBody();
                $data = json_decode($jsonData);

                if (!$data || !isset($data->address)) {
                    throw new \Exception('Адрес не указан');
                }

                $address = $data->address;

                $response = $client->request('GET', 'https://geocode-maps.yandex.ru/1.x/', [
                    'query' => [
                        'apikey' => Yii::$app->params['yandex_map_api_key'],
                        'lang' => 'ru',
                        'format' => 'json',
                        'geocode' => $address
                    ]
                ]);

                $body = $response->getBody();
                $geocodeData = json_decode($body, true);

                // Парсим ответ от Яндекс Геокодера
                if (isset($geocodeData['response']['GeoObjectCollection']['featureMember'])) {
                    $features = $geocodeData['response']['GeoObjectCollection']['featureMember'];
                    $results = [];

                    foreach ($features as $feature) {
                        $geoObject = $feature['GeoObject'];
                        $pos = $geoObject['Point']['pos'];
                        list($lng, $lat) = explode(' ', $pos);

                        $fullAddress = $geoObject['metaDataProperty']['GeocoderMetaData']['text'];

                        $results[] = [
                            'address' => $fullAddress,
                            'lat' => (float)$lat,
                            'lng' => (float)$lng
                        ];
                    }

                    return $this->asJson([
                        'data' => $results,
                        'success' => true,
                        'count' => count($results) // Добавляем количество найденных вариантов
                    ]);
                } else {
                    throw new \Exception('Адреса не найдены');
                }

            } catch (\Exception $e) {
                return $this->asJson([
                    'success' => false,
                    'error' => $e->getMessage()
                ]);
            }
        }

        throw new \yii\web\BadRequestHttpException('Только AJAX запросы');
    }

    public function init()
    {
        parent::init();
        Yii::$app->user->loginUrl = ['landing'];
    }
}
