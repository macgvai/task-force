<?php

namespace app\controllers;

use app\models\Category;
use app\models\File;
use app\models\SearchModel;
use app\models\Status;
use app\models\Task;
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
        $queryTasks =  $tasksModel->getFilters();

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

        // Проверяем, существует ли задание
        if ($task === null) {
            throw new NotFoundHttpException('Задание не найдено.');
        }

        return $this->render('view', [
            'task' => $task, // Передаем задание в представление
            'replies' => $replies, // Передаем отклики
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




    public function init()
    {
        parent::init();
        Yii::$app->user->loginUrl = ['landing'];
    }
}
