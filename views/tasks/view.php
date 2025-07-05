<?php

use app\helpers\UIHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use function morphos\Russian\pluralize;

?>
<main class="main-content container">
    <div class="left-column">
        <div class="head-wrapper">
            <h3 class="head-main"><?= Html::encode($task->name) ?></h3>
            <p class="price price--big"><?= $task->budget ?>₽</p>
        </div>
        <p class="task-description"> <?= Html::encode($task->description) ?> </p>
        <a href="#" class="button button--blue">Откликнуться на задание</a>
        <div class="task-map">
            <img class="map" src="img/map.png"  width="725" height="346" alt="Новый арбат, 23, к. 1">
            <p class="map-address town">Москва</p>
            <p class="map-address">Новый арбат, 23, к. 1</p>
        </div>
        <h4 class="head-regular">Отклики на задание</h4>
        <?php foreach ($replies as $repl): ?>
            <!-- Отклик на задание -->
            <div class="response-card">
                <img class="customer-photo" src="<?= !empty($repl->user->avatar) ? $repl->user->avatar : '/img/avatars/3.png' ?>" width="146" height="156" alt="Фото заказчиков">
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
                <div class="button-popup">
                    <a href="#" class="button button--blue button--small">Принять</a>
                    <a href="#" class="button button--orange button--small">Отказать</a>
                </div>
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
</main>