<?php

namespace app\controllers;

use app\models\User;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

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

    public function actionSettings()
    {
        /**
         * @var User $user
         */
        $user = $this->getUser();
        $user->setScenario('update');

        if (\Yii::$app->request->isPost) {

            $user->load(\Yii::$app->request->post());
            $user->avatarFile = UploadedFile::getInstance($user, 'avatarFile');


            $user->password_repeat = $user->password;
            if ($user->save()) {
                return $this->redirect(['user/view', 'id' => $user->id]);
            }
        }


        return $this->render('edit-profile', ['user' => $this->getUser()]);
    }




}