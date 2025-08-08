<?php

namespace app\controllers;

use app\models\LoginForm;
use yii\web\Controller;

class LandingController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
}