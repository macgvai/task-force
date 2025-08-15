<?php

namespace app\controllers;

use app\models\Category;
use app\models\SearchModel;
use app\models\Task;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
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

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());

            // AJAX-валидация
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model); // ← ранний выход
            }

            if ($model->validate()) {
                if ($model->save(false)) {
                    return $this->goHome();
                } else {
                    Yii::error($model->getErrors(), 'UserSaveError');
                }
            }
        }

        return $this->render('create', [
            'model' => $model,
            'categories' => $category
        ]);
    }




    public function init()
    {
        parent::init();
        Yii::$app->user->loginUrl = ['landing'];
    }
}
