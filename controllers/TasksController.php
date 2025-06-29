<?php

namespace app\controllers;

use app\models\Categories;
use app\models\SearchModel;
use app\models\Tasks;
use victor\logic\AvailableActions;
use Yii;
use yii\web\Controller;

class TasksController extends Controller
{
    public function actionIndex(){
        // Создаем модель для заданий
        $tasksModel = new Tasks();
        $tasksModel->load(Yii::$app->request->post());
        $queryTasks =  $tasksModel->getFilters();

        // Получаем все категории
        $categories = Categories::find()->all();

        $tasks = $queryTasks->all();

        return $this->render('index', [
            'tasksModel' => $tasksModel,
            'tasks' => $tasks,
            'categories' => $categories
        ]);
    }
}
