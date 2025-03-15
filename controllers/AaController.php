<?php

namespace app\controllers;
use yii\web\Controller;

class AaController extends Controller
{
    public function actionIndex()
    {
        \Yii::$app->db->open(); // проверка, что параметры подключения к БД установлены верно
        print_r('gesrgergeagr');
        echo '$contact->name, $contact->phone, $contact->company->name';
        die();
    }
}