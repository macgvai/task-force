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

        // Получаем все категории
        $categories = Categories::find()->all();

        // Получаем задачи со статусом 1
        $tasks = Tasks::find()->where(['status_id' => 1])->all();

        // Обработка POST-запроса
        if ($tasksModel->load(Yii::$app->request->post())) {
            dd($tasksModel);
            // Логика сохранения или поиска
            // Например, фильтрация задач
            $tasks = Tasks::find()->andFilterWhere(['=', 'category', $tasksModel->id])->all();
        }

        return $this->render('index', [
            'tasksModel' => $tasksModel,
            'tasks' => $tasks,
            'categories' => $categories
        ]);
    }

}