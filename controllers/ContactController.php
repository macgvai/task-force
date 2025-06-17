<?php

namespace app\controllers;

use app\models\Contact;
use yii\web\Controller;

class ContactController extends Controller
{
    public function actionIndex()
    {

        $contact = Contact::find()->all();
//        return $this->render('index');
    }
}