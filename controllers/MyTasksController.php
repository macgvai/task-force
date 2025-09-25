<?php

namespace app\controllers;

use app\helpers\UIHelper;
use app\models\Task;
use Yii;

class MyTasksController extends SecureController
{
    public function actionIndex($status = null) {
        $tasks = Task::find()->where(['client_id' => Yii::$app->user->id])->all();

        $menuItems = UIHelper::getMyTasksMenu($this->getUser()->is_contractor);

        if (!$status) {
            $this->redirect($menuItems[0]['url']);
        }

        $tasks = $this->getUser()->getTasksByStatus($status)->all();

        return $this->render('index', ['menuItems' => $menuItems, 'tasks' => $tasks]);
    }

}