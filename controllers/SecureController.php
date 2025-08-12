<?php

namespace app\controllers;

use yii\filters\AccessControl;
use yii\web\Controller;

class SecureController extends Controller
{
    public function behaviors(): array
    {

        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@']
                    ],
//                    [
//                        'allow' => false,
//                        'actions' => ['update'],
//                        'matchCallback' => function ($rule, $action) {
//                            $id = Yii::$app->request->get('id');
//                            $contact = Contact::findOne($id);
//
//                            return $contact->owner_id != Yii::$app->user->getId();
//                        }
//                    ]
                ]
            ]
        ];
    }
}