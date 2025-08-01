<?php

namespace app\controllers;

use app\models\User;
use yii\data\ActiveDataProvider;
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
//    $searchModel = new YourModelSearch(); // Предполагается, что у вас есть SearchModel
    $data = User::find();
    $dataProvider = new ActiveDataProvider([
        'query' => $data,
    ]);

    return $this->render('index', [
        'dataProvider' => $dataProvider,
    ]);
}

}