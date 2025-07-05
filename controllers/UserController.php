<?php

namespace app\controllers;

use app\models\Users;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class UserController extends Controller
{
    public function actionIndex()
    {
        echo 'User';
    }

    public function actionView($id)
    {
        $user = Users::findOne($id);

        if (!$user) {
            throw new NotFoundHttpException('Пользователь не найден');
        }

        return $this->render('view', ['user' => $user]);
    }

}