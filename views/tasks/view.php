<?php

use app\helpers\UIHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use function morphos\Russian\pluralize;
$user = Yii::$app->user->getIdentity();

?>
<div class="left-column">
    <div class="head-wrapper">
        <h3 class="head-main"><?= Html::encode($task->name) ?></h3>
        <p class="price price--big"><?= $task->budget ?>₽</p>
    </div>
    <p class="task-description"> <?= Html::encode($task->description) ?> </p>

    <?php foreach (UIHelper::getActionButtons($task, $user) as $button): ?>
        <?=$button;?>
    <?php endforeach; ?>

    <div class="task-map">
        <div id="map" class="map"></div>

<!--        <img class="map" src="img/map.png"  width="725" height="346" alt="Новый арбат, 23, к. 1">-->
<!--        <p class="map-address town">Москва</p>-->
        <p class="map-address"><?= $task->address->address ?></p>
<!--        <p class="map-address">Новый арбат, 23, к. 1</p>-->
    </div>
    <h4 class="head-regular">Отклики на задание</h4>
    <?php foreach ($task->getReplies($user)->all() as $repl): ?>
        <!-- Отклик на задание -->
        <div class="response-card">
            <img class="customer-photo" src="<?= !empty($repl->user->avatar) ? Yii::getAlias('@web/' . ltrim($repl->user->avatar))  : Yii::getAlias('@web/img/avatars/3.png') ?>" width="146" height="156" alt="Фото заказчиков">
            <div class="feedback-wrapper">
                <a href=" <?= Url::to(['user/view', 'id' => $repl->user_id])?> " class="link link--block link--big"> <?= Html::encode($repl->user->name) ?> </a>
                <div class="response-wrapper">
                    <?= UIHelper::showStarRating($repl->user->rating) ?>
                    <?php $reviewsCount = $repl->user->getOpinions()->count() ?>
                    <p class="reviews"><?= pluralize($reviewsCount, 'отзыв')?> </p>
                </div>
                <p class="response-message">
                   <?= Html::encode($repl->description) ?>
                </p>

                </div>
                <div class="feedback-wrapper">
                    <p class="info-text"><span class="current-time"> <?= Yii::$app->formatter->asRelativeTime($repl->dt_add) ?> </span></p>
                    <p class="price price--small"> <?= $repl->budget ?>₽</p>
                </div>

            <?php if ($task->client_id == Yii::$app->user->id && !$repl->is_denied): ?>
                <div class="button-popup">
                    <a href="<?= Url::to(['/reply/approve', 'id' => $repl->id]) ?>" class="button button--blue button--small">Принять</a>
                    <a href="<?= Url::to(['/reply/deny', 'id' => $repl->id]) ?>" class="button button--orange button--small">Отказать</a>
                </div>
            <?php endif; ?>
            </div>

    <?php endforeach; ?>
</div>
<div class="right-column">
    <div class="right-card black info-card">
        <h4 class="head-card">Информация о задании</h4>
        <dl class="black-list">
            <dt>Категория</dt>
            <dd><?=Html::encode( $task->category->name) ?></dd>
            <dt>Дата публикации</dt>
            <dd><?= Yii::$app->formatter->asRelativeTime($task->dt_add) ?></dd>
            <dt>Срок выполнения</dt>
            <dd><?= Yii::$app->formatter->asDatetime($task->expire_dt, 'php:d F H:i') ?></dd>
            <dt>Статус</dt>
            <dd><?=Html::encode( $task->status->name) ?></dd>
        </dl>
    </div>
    <div class="right-card white file-card">
        <h4 class="head-card">Файлы задания</h4>
        <ul class="enumeration-list">
            <li class="enumeration-item">
                <a href="#" class="link link--block link--clip">my_picture.jpg</a>
                <p class="file-size">356 Кб</p>
            </li>
            <li class="enumeration-item">
                <a href="#" class="link link--block link--clip">information.docx</a>
                <p class="file-size">12 Кб</p>
            </li>
        </ul>
    </div>
</div>

<section class="pop-up pop-up--act_deny pop-up--close">
    <div class="pop-up--wrapper">
        <h4>Отказ от задания</h4>
        <p class="pop-up-text">
            <b>Внимание!</b><br>
            Вы собираетесь отказаться от выполнения этого задания.<br>
            Это действие плохо скажется на вашем рейтинге и увеличит счетчик проваленных заданий.
        </p>
        <a class="button button--pop-up button--orange" href="<?=Url::to(['tasks/deny', 'id' => $task->id]); ?>">Отказаться</a>
        <div class="button-container">
            <button class="button--close" type="button">Закрыть окно</button>
        </div>
    </div>
</section>
<section class="pop-up pop-up--act_complete pop-up--close">
    <div class="pop-up--wrapper">
        <h4>Завершение задания</h4>
        <p class="pop-up-text">
            Вы собираетесь отметить это задание как выполненное.
            Пожалуйста, оставьте отзыв об исполнителе и отметьте отдельно, если возникли проблемы.
        </p>
        <div class="completion-form pop-up--form regular-form">
            <?php $form = ActiveForm::begin([
                'action' => Url::to(['opinion/create', 'task' => $task->id]),
                'enableAjaxValidation' => true,
                'validationUrl' => ['opinion/validate'],
            ]); ?>
            <?= $form->field($opinion, 'description')->textarea(); ?>
            <?= $form->field($opinion, 'rate', ['template' => '{label}{input}' . UIHelper::showStarRating(0, 'big', 5, true) . '{error}'])
                ->hiddenInput(); ?>
            <input type="submit" class="button button--pop-up button--blue" value="Завершить">
            <?php ActiveForm::end(); ?>
        </div>
        <div class="button-container">
            <button class="button--close" type="button">Закрыть окно</button>
        </div>
    </div>
</section>
<section class="pop-up pop-up--act_response pop-up--close">
    <div class="pop-up--wrapper">
        <h4>Добавление отклика к заданию</h4>
        <p class="pop-up-text">
            Вы собираетесь оставить свой отклик к этому заданию.
            Пожалуйста, укажите стоимость работы и добавьте комментарий, если необходимо.
        </p>
        <div class="addition-form pop-up--form regular-form">
            <?php $form = ActiveForm::begin(['enableAjaxValidation' => true,
                    'validationUrl' => ['reply/validate', 'task' => $task->id],
                    'action' => Url::to(['reply/create', 'task' => $task->id])]
            );
            ?>
            <?= $form->field($newReply, 'description')->textarea(); ?>
            <?= $form->field($newReply, 'budget'); ?>
            <input type="submit" class="button button--pop-up button--blue" value="Отправить">
            <?php ActiveForm::end(); ?>
        </div>
        <div class="button-container">
            <button class="button--close" type="button">Закрыть окно</button>
        </div>
    </div>
</section>

<?php
    $lat = $task->address->lat;
    $lng = $task->address->lng;

    $this->registerJs(
    <<<JS
    initMap();
    
    async function initMap() {
        await ymaps3.ready;
    
        const { YMap, YMapDefaultSchemeLayer, YMapMarker, YMapDefaultFeaturesLayer } = ymaps3;
    
        const map = new YMap(
            document.getElementById('map'),
            {
                location: {
                    center: [$lng, $lat],
                    zoom: 10
                }
            }
        );
    
        map.addChild(new YMapDefaultSchemeLayer());
        map.addChild(new YMapDefaultFeaturesLayer());
    
        // создаём элемент для маркера
        const markerElement = document.createElement('div');
        markerElement.className = 'my-marker';
        markerElement.textContent = '📍';
    
        // добавляем маркер
        const marker = new YMapMarker({ coordinates: [$lng, $lat] }, markerElement);
        map.addChild(marker);
    }
JS

    )
?>
