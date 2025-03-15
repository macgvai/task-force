<?php

namespace app\controllers;
use yii\web\Controller;

class Test extends Controller
{
    public function actionIndex()
    {
        \Yii::$app->db->open(); // проверка, что параметры подключения к БД установлены верно
        print_r('aaaaaaaaaaaaaaaaaa');
        return $this->render('index');
    }
}