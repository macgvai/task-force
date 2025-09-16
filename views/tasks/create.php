<?php

use app\assets\DropZoneAsset;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\ActiveForm;

$this->title = 'Создание задания';
$this->params['main_class'] = 'main-content--center';

// Подключаем autoComplete.js
$this->registerCssFile('https://cdn.jsdelivr.net/npm/@tarekraafat/autocomplete.js@10.2.7/dist/css/autoComplete.02.min.css');
$this->registerJsFile('https://cdn.jsdelivr.net/npm/@tarekraafat/autocomplete.js@10.2.7/dist/autoComplete.min.js', ['position' => View::POS_HEAD]);

DropZoneAsset::register($this);
?>

    <div class="add-task-form regular-form">
        <?php $form = ActiveForm::begin(['id' => 'task-form']); ?>
        <h3 class="head-main head-main">Публикация нового задания</h3>

        <?= $form->field($model, 'name')->label('Опишите суть работы')->textInput() ?>
        <?= $form->field($model, 'description')->label('Подробности задания')->textarea() ?>
        <?= $form->field($model, 'category_id')->label('Категория')->dropDownList($categories) ?>

        <!-- Поле локации с автокомплитом -->
        <div class="form-group field-task-location">
            <label class="control-label" for="location-input">Локация</label>
            <input
                   id="location-input"
                   class="form-control location-input"
                   placeholder="Введите адрес..."
                   type="search"
                   spellcheck=false
                   autocomplete="off"
                   autocapitalize="off"
                   maxlength="2048"
                   tabindex="1"
                   >
            <div class="help-block">Начните вводить адрес и выберите из предложенных вариантов</div>
            <div class="help-block error-message" id="location-error" style="display: none; color: #a94442;"></div>
        </div>

        <?= $form->field($model, 'location')->hiddenInput(['id' => 'location'])->label(false) ?>
        <?= $form->field($model, 'lat')->hiddenInput(['id' => 'lat'])->label(false) ?>
        <?= $form->field($model, 'lng')->hiddenInput(['id' => 'lng'])->label(false) ?>

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
// Функция для отображения ошибок
function showLocationError(message) {
    const errorElement = document.getElementById('location-error');
    errorElement.textContent = message;
    errorElement.style.display = 'block';
}


// Инициализация autoComplete.js
function initAutocomplete() {
    const locationInput = document.getElementById('location-input');
    const hiddenLocation = document.getElementById('location');
    const hiddenLat = document.getElementById('lat');
    const hiddenLng = document.getElementById('lng');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    if (!locationInput) return;

    const autoCompleteJS = new autoComplete({
        selector: "#location-input",
        placeHolder: "Введите адрес...",
        debounce: 500,
        // data: {
        //  src: ["Sauce - Thousand Island", "Wild Boar - Tenderloin", "Goat - Whole Cut"]
        // },
        data: {
            src: async (query) => {
                try {
                    const response = await fetch('/tasks/search-geo-position', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-Token': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({ address: query })
                    });

                    if (!response.ok) {
                        throw new Error('Ошибка сервера: ' + response.status);
                    }
                  
                    const data = await response.json();

                    return data.data;

                } catch (error) {
                    console.error('Ошибка при поиске адреса:', error);
                    return [];
                }
            },
            keys: ['address'],
            cache: false,
        },
        resultItem: {
            highlight: true
        },
        events: {
            input: {
                selection: (event) => {
                    const selection = event.detail.selection.value;

                    // Заполняем скрытые поля
                    hiddenLocation.value = selection.address + ' lat:' + selection.lat + ' lng:' +selection.lng;
                    // hiddenLat.value = selection.lat || '';
                    // hiddenLng.value = selection.lng || '';
                  
                    // Обновляем видимое поле
                    locationInput.value = selection.address;
                  
                    console.log('Выбрано местоположение:', {
                        address: selection.address,
                        lat: selection.lat,
                        lng: selection.lng
                    });
                },
                focus: () => {
                    if (locationInput.value.length >= 3) {
                        autoCompleteJS.start();
                    }
                }
            }
        }
    });

    // Валидация перед отправкой формы
    document.getElementById('task-form').addEventListener('submit', function(e) {
        if (locationInput.value && !hiddenLocation.value) {
            e.preventDefault();
            showLocationError('Пожалуйста, выберите адрес из списка предложенных вариантов');
            locationInput.focus();
        }
    });
    console.log(autoCompleteJS)
}


// Инициализация при загрузке DOM
document.addEventListener('load', function() {
    // Ждем загрузки autoComplete.js
    debugger
    if (typeof autoComplete !== 'undefined') {
        initAutocomplete();
    } else {
        // Если библиотека еще не загружена, ждем
        const interval = setInterval(() => {
            if (typeof autoComplete !== 'undefined') {
                clearInterval(interval);
                initAutocomplete();
            }
        }, 100);
    }
});
initAutocomplete();

// Dropzone initialization
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

JS, View::POS_READY);
?>