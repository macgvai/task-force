<?php

use yii\widgets\ActiveForm;
use yii\jui\DatePicker;
?>

<div class="add-task-form regular-form">
    <?php $form = ActiveForm::begin([
        'enableAjaxValidation' => true,
        'validationUrl' => '/tasks/create'
    ]); ?>
    <h3 class="head-main head-main">Публикация нового задания</h3>

    <?= $form->field($model, 'name')->label('Опишите суть работы')->textInput() ?>
    <?= $form->field($model, 'description')->label('Подробности задания')->textarea() ?>
    <?= $form->field($model, 'category_id')->label('Категория')->dropDownList($categories) ?>
    <?= $form->field($model, 'location')->label('Локация')->textInput() ?>
    <div class="half-wrapper">
        <?= $form->field($model, 'budget')->label('Бюджет')->textInput() ?>
        <?= $form->field($model, 'expire_dt')->label('Срок исполнения')->input('date') ?>
    </div>

    <p class="form-label">Файлы</p>
    <div class="new-file">
        Добавить новый файл
    </div>

    <input type="submit" class="button button--blue" value="Опубликовать">
    <?php ActiveForm::end() ?>

</div>