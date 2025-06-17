<?php

namespace app\models;

use yii\db\ActiveRecord;

class Contact extends ActiveRecord
{
    public function attributeLabels() {
        return [
            'name' => 'Имя',
            'phone' => 'Телефон',
            'email' => 'Email',
            'position' => 'Должность',
        ];
    }

    public static function tableName()
    {
        return 'contact'; // Или '{{%contact}}' если используете префиксы таблиц
    }
}


