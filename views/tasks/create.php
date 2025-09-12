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
    <?= $form->field($model, 'location')->label('Локация')->textInput(['class' => 'location']) ?>
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
    maxFiles: 4, 
    url: "$uploadUrl", 
    previewsContainer: ".files-previews",
    previewTemplate: 
    `<div class="dz-preview dz-file-preview">
        <div class="dz-details">
            <img data-dz-thumbnail />
            <div class="dz-filename"><span data-dz-name></span></div>
            <div class="dz-size" data-dz-size></div>
        </div>
    </div>`,
    sending: function (none, xhr, formData) {
        formData.append('_csrf', $('input[name=_csrf]').val());
    }
});

const location = $('.location');
let debounceTimer;

location.on('input', function (event) {
    const address = event.target.value.trim();
    
    clearTimeout(debounceTimer);
    
    if (address.length < 3) {
        return;
    }
    
    debounceTimer = setTimeout(() => {
        console.log('Поиск адреса:', address);
        
        const csrfToken = $('meta[name="csrf-token"]').attr('content');
        
        fetch('/tasks/search-geo-position', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': csrfToken,
                'X-Requested-With': 'XMLHttpRequest' // Добавляем AJAX заголовок
            },
            body: JSON.stringify({
                address: address,
                // _csrf: csrfToken
            })
        })
        .then(response => response.json())
        .then(data => {
            console.log('Результаты геопоиска:', data);
        })
        .catch(error => {
            console.error('Ошибка геопоиска:', error);
        });
    }, 500);
});


JS, View::POS_READY);
?>