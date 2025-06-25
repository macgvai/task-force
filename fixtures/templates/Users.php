<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

use app\models\Cities;
use yii\db\Expression;

return [
    'email' => $faker->unique()->email,
    'name' => $faker->name,
    'city_id' => Cities::find()->select('id')->orderBy(new Expression('RANDOM()'))->scalar(),
    'password' => Yii::$app->security->generatePasswordHash('qwerty'),
];