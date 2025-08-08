<?php

namespace app\controllers;

use app\models\City;
use app\models\LoginForm;
use app\models\User;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Response;

class AuthController extends Controller
{
//    public function actionIndex()
//    {
//        return $this->render('registration');
//    }

    public function actionSignup()
    {
        $model = new User();
        $cities =  ArrayHelper::map(City::find()->all(), 'id', 'name');

        if (Yii::$app->request->getIsPost()) {

//            if (Yii::$app->request->isAjax) {
//                Yii::$app->response->format = Response::FORMAT_JSON;
//            }

            $model->load(Yii::$app->request->post());
            if ($model->validate()) {
                $model->password = Yii::$app->security->generatePasswordHash($model->password);

                // Попытка сохранить модель
                if ($model->save(false)) {
                    return $this->goHome();
                } else {
                    // Если save() вернул false, выведите ошибки
                    Yii::error($model->getErrors(), 'UserSaveError');
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
            'cities' => $cities
            ]);
    }

    public function actionLogin()
    {
        $loginForm = new LoginForm();

        if (Yii::$app->request->getIsPost())
        {
            $loginForm->load(Yii::$app->request->post());

            if ($loginForm->validate()) {
                $user = $loginForm->getUser();

                Yii::$app->user->login($user);
                return $this->goHome();
            }
        }

        return $this->render('index', ['model' => $loginForm]);
    }

    public function actionProfile()
    {
        if ($id = \Yii::$app->user->getId()) {
            $user = User::findOne($id);

            print($user->email);
        }
    }

    public function actionLogout() {
        \Yii::$app->user->logout();

        return $this->goHome();
    }
}