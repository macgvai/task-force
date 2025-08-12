<?php

namespace app\controllers;

use app\models\User;
use Yii;
use yii\web\NotFoundHttpException;

class UserController extends SecureController
{
    public function actionIndex()
    {
        echo 'User';
    }

    public function actionView($id)
    {
        $user = User::findOne($id);

        if (!$user) {
            throw new NotFoundHttpException('Пользователь не найден');
        }

        return $this->render('view', ['user' => $user]);
    }

    public function actionLogout() {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionProfile()
    {
        if ($id = Yii::$app->user->getId()) {
            $user = User::findOne($id);

            print($user->email);
        }
    }

}