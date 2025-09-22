<?php


use yii\web\View;
use yii\widgets\ActiveForm;

?>
<main class="container container--registration">
    <div class="center-block">
        <div class="registration-form regular-form">
            <?php $form = ActiveForm::begin() ?>

                <h3 class="head-main head-task">Регистрация нового пользователя</h3>
                <?= $form->field($model, 'name')->label('Ваше имя')->textInput() ?>
                <div class="half-wrapper">
                    <?= $form->field($model, 'email')->label('Email')->textInput() ?>
                    <?= $form->field($model, 'city_id')->label('Город')->dropDownList($cities) ?>
                </div>
                <?= $form->field($model, 'password')->label('Пароль')->passwordInput() ?>
                <?= $form->field($model, 'password_repeat')->label('Повтор пароля')->passwordInput() ?>
                <?= $form->field($model, 'is_contractor')
                    ->checkbox(['labelOptions' => ['class' => 'control-label checkbox-label']]); ?>

            <button id="container"></button>

            <?php try {
                yii\authclient\widgets\AuthChoice::widget([
                    'baseAuthUrl' => ['site/auth']
                ]);
            } catch (Throwable $e) {

            } ?>

            <div class="form-group">
                <input type="submit" class="button button--blue" value="Создать аккаунт">
            </div>
            <?php ActiveForm::end() ?>
        </div>
    </div>
</main>
<!--    client_id: 'c0140e7e54bd4326842c51cbec844e9c',-->

<?php
$this->registerJs(<<<JS
    YaAuthSuggest.init(
        {
            client_id: 'c0140e7e54bd4326842c51cbec844e9c',
            response_type: 'code',
            redirect_uri: 'http://localhost:8080/auth/yandex',
            scope: 'login:email'
        },
        'http://localhost:8080/auth/yandex', 
        {
            view: 'button',
            parentId: 'container',
            buttonView: 'main',
            buttonTheme: 'light',
            buttonSize: 'm',
            buttonBorderRadius: 0
        }
    )
    .then(function(result) {
       return result.handler()
    })
    .then(function(data) {
       console.log('Сообщение с токеном: ', data);
       document.body.innerHTML += 'Сообщение с токеном:' + JSON.stringify(data);
       debugger
    })
    .catch(function(error) {
       console.log('Что-то пошло не так: ', error);
       document.body.innerHTML += 'Сообщение с токеном:' + JSON.stringify(data);
    });


JS, View::POS_READY);
?>