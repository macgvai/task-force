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

            <div class="form-group">
                <input type="submit" class="button button--blue" value="Создать аккаунт">
            </div>
            <?php ActiveForm::end() ?>
        </div>
    </div>
</main>