<?php

namespace app\controllers;

use yii\web\Controller;
use victor\exceptions\ConverterException;

class SiteController extends Controller
{

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        \Yii::$app->db->open(); // проверка, что параметры подключения к БД установлены верно
//        print_r('aaaaaaaaaaaaaaaaaa');
        return $this->render('index');
    }

}