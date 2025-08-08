<?php

namespace app\controllers;

use app\models\Category;
use app\models\SearchModel;
use app\models\Task;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class TasksController extends Controller
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




    public function init()
    {
        parent::init();
        Yii::$app->user->loginUrl = ['landing'];
    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@']
                    ],
//                    [
//                        'allow' => false,
//                        'actions' => ['update'],
//                        'matchCallback' => function ($rule, $action) {
//                            $id = Yii::$app->request->get('id');
//                            $contact = Contact::findOne($id);
//
//                            return $contact->owner_id != Yii::$app->user->getId();
//                        }
//                    ]
                ]
            ]
        ];
    }

}
