<?php

use app\assets\DropZoneAsset;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\ActiveForm;

$this->title = 'Создание задания';
$this->params['main_class'] = 'main-content--center';

DropZoneAsset::register($this);
?>

<div class="add-task-form regular-form">
    <?php $form = ActiveForm::begin(); ?>
    <h3 class="head-main head-main">Публикация нового задания</h3>

    <?= $form->field($model, 'name')->label('Опишите суть работы')->textInput() ?>
    <?= $form->field($model, 'description')->label('Подробности задания')->textarea() ?>
    <?= $form->field($model, 'category_id')->label('Категория')->dropDownList($categories) ?>
    <?= $form->field($model, 'location')->label('Локация')->textInput() ?>
    <div class="half-wrapper">
        <?= $form->field($model, 'budget')->label('Бюджет')->textInput(['class' => 'budget-icon']) ?>
        <?= $form->field($model, 'expire_dt')->label('Срок исполнения')->input('date') ?>
    </div>

    <p class="form-label">Файлы</p>
    <div class="new-file">
        Добавить новый файл
    </div>
    <div class="files-previews"></div>

    <input type="submit" class="button button--blue" value="Опубликовать">
    <?php ActiveForm::end() ?>

</div>

<?php
$uploadUrl = Url::toRoute(['tasks/upload']);
$this->registerJs(<<<JS
var myDropzone = new Dropzone(".new-file", {
    maxFiles: 4, url: "$uploadUrl", previewsContainer: ".files-previews",
    sending: function (none, xhr, formData) {
        formData.append('_csrf', $('input[name=_csrf]').val());
    }
    });
JS, View::POS_READY);
?>