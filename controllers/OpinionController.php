<?php

namespace app\controllers;

use app\models\Opinion;
use app\models\Task;
use victor\logic\actions\CompleteAction;
use Yii;
use yii\web\Response;
use yii\widgets\ActiveForm;

class OpinionController extends SecureController
{
    public function actionCreate($task)
    {
        /**
         * @var Task $task
         */
        $task = $this->findOrDie($task, Task::class);
        $opinion = new Opinion();

        if (Yii::$app->request->isPost) {
            $opinion->load(Yii::$app->request->post());
            $opinion->performer_id = $task->performer_id;
            $opinion->owner_id = Yii::$app->user->id;

            if ($opinion->validate()) {
                $task->link('opinions', $opinion);
                $task->goToNextStatus(new CompleteAction);
            }
        }

        return $this->redirect(['tasks/view', 'id' => $task->id]);
    }

    public function actionValidate()
    {
        $opinion = new Opinion();

        if (Yii::$app->request->isAjax && $opinion->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($opinion);
        }
    }

}