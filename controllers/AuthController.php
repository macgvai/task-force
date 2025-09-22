<?php

namespace app\controllers;

use app\models\Auth;
use app\models\City;
use app\models\LoginForm;
use app\models\User;
use GuzzleHttp\Client;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\Response;
use yii\web\ServerErrorHttpException;
use yii\widgets\ActiveForm;

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
                    Yii::$app->user->login($model);
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

            // AJAX-валидация
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($loginForm);
            }

            if ($loginForm->validate()) {
                $user = $loginForm->getUser();

                Yii::$app->user->login($user);
                return $this->goHome();
            }
        }
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


    public function actions()
    {
        return [
            'yandex' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'onAuthSuccess'],
            ],
        ];
    }

    public function onAuthSuccess($client)
    {
        $userData = $client->getUserAttributes();

        $auth = Auth::find()->where(['source' => $client->getId(), 'source_id' => $userData['id']])->one();

        if ($auth) {
            Yii::$app->user->login($auth->user);
        } else {
            if (isset($userData['email']) && User::find()->where(['email' => $userData['email']])->exists()) {
                Yii::$app->getSession()->setFlash('error', [
                    Yii::t('app', "Пользователь с такой электронной почтой как в {client} уже существует, но с ним не связан"),
                ]);
            } else {
                $city = City::find()->one();

                if (isset($userData['city']['title'])) {
                    $city = City::find()->where(['name' => $userData['city']['title']])->one();
                }

                $password = Yii::$app->security->generateRandomString(6);
                $user = new User([
                    'name' => $userData['first_name'],
                    'email' => $userData['default_email'],
                    'city_id' => $city->id,
                    'password' => Yii::$app->security->generatePasswordHash($password),
                ]);

                if ($user->save(false)) {
                    $auth = new Auth([
                        'user_id' => $user->id,
                        'source' => $client->getId(),
                        'source_id' => (string) $userData['id'],
                    ]);
                    $auth->save();

                    Yii::$app->user->login($user);
                }
            }
        }

        $this->goHome();
    }
}