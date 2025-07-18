<?php

namespace app\controllers;

use app\models\Cities;
use app\models\Users;
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
        $model = new Users();
        $cities =  ArrayHelper::map(Cities::find()->all(), 'id', 'name');

        if (Yii::$app->request->getIsPost()) {

//            if (Yii::$app->request->isAjax) {
//                Yii::$app->response->format = Response::FORMAT_JSON;
//            }

            $model->load(Yii::$app->request->post());
            if ($model->validate()) {
                $model->password = Yii::$app->security->generatePasswordHash($model->password);

                $model->save();
            }
            return $this->goHome();
        }

        return $this->render('signup', [
            'model' => $model,
            'cities' => $cities
            ]);
    }
}