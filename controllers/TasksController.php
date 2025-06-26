<?php

namespace app\controllers;

use app\models\Tasks;
use victor\logic\AvailableActions;
use yii\web\Controller;

class TasksController extends Controller
{
    public function actionIndex(){
        $tasks = Tasks::findAll(['status_id' => 1]);

        $actions = AvailableActions::ROLE_PERFORMER;

        return $this->render('index', ['tasks' => $tasks]);
    }

}