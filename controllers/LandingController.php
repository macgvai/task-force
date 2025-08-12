<?php

namespace app\controllers;

use yii\web\Controller;

class LandingController extends Controller
{
    public function actionIndex()
    {
        // Установка layout'а для страницы
        $this->layout = 'landing';

        // Возвращаем рендер представления
        return $this->render('index');
    }
}
