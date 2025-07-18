<?php

use app\helpers\UIHelper;
use yii\helpers\Html;
use yii\helpers\Url;

$user;

?>

<main class="main-content container">
    <div class="left-column">
        <h3 class="head-main"><?= Html::encode($user->name) ?></h3>
        <div class="user-card">
            <div class="photo-rate">
                <img class="card-photo" src="<?= !empty($user->avatar) ? Yii::getAlias('@web/' . ltrim($user->avatar, '/')) : Yii::getAlias('@web/img/avatars/3.png') ?>" width="191" height="190" alt="Фото пользователя">
                <div class="card-rate">
                    <?= UIHelper::showStarRating($user->getRating()); ?>
                    <span class="current-rate"> <?= $user->getRating(); ?> </span>
                </div>
            </div>
            <p class="user-description"> <?= $user->description ?> </p>
        </div>
        <div class="specialization-bio">
            <div class="specialization">
                <p class="head-info">Специализации</p>
                <ul class="special-list">
                    <?php foreach ($user->userCategories as $category): ?>
                        <li class="special-item">
                            <a href="#" class="link link--regular"><?=$category->name; ?></a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="bio">
                <p class="head-info">Био</p>
                <p class="bio-info"><span class="country-info">Россия</span>, <span class="town-info">Петербург</span>, <span class="age-info">30</span> лет</p>
            </div>
        </div>

        <?php if ($user->opinions): ?>
            <h4 class="head-regular">Отзывы заказчиков</h4>

            <?php foreach ($user->opinions as $opinion): ?>
                <div class="response-card">
                    <img class="customer-photo" src="<?=$opinion->owner->avatar; ?>" width="120" height="127" alt="Аватар заказчика">
                    <div class="feedback-wrapper">
                        <p class="feedback">«<?=Html::encode($opinion->description); ?>»</p>
                        <p class="task">Задание «<a href="<?=Url::to(['tasks/view', 'id' => $opinion->task_id]); ?>"
                                                    class="link link--small"><?=Html::encode($opinion->task->name); ?></a>» выполнено</p>
                    </div>
                    <div class="feedback-wrapper">
                        <?=UIHelper::showStarRating($opinion->rate); ?>
                        <p class="info-text"><span class="current-time"><?=Yii::$app->formatter->asRelativeTime($opinion->dt_add); ?></span></p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif ?>
    </div>
    <div class="right-column">

        <div class="right-card black">
            <h4 class="head-card">Статистика исполнителя</h4>
            <dl class="black-list">
                <dt>Всего заказов</dt>
                <dd><?=$user->getAssignedTasks()->count(); ?> выполнено, <?=$user->fail_count;?> провалено</dd>
                <?php if ($position = $user->getRatingPosition()): ?>
                    <dt>Место в рейтинге</dt>
                    <dd><?=$position; ?> место</dd>
                <?php endif ?>
                <dt>Дата регистрации</dt>
                <dd><?=Yii::$app->formatter->asDate($user->dt_add); ?></dd>
                <dt>Статус</dt>
                <?php if (!$user->isBusy()): ?>
                    <dd>Открыт для новых заказов</dd>
                <?php else: ?>
                    <dd>Занят</dd>
                <?php endif ?>
            </dl>
        </div>

        <?php if ($user->isContactsAllowed(Yii::$app->user->getIdentity())): ?>
            <div class="right-card white">
                <h4 class="head-card">Контакты</h4>
                <ul class="enumeration-list">
                    <?php if ($user->userSettings->phone): ?>
                        <li class="enumeration-item">
                            <a href="tel:<?= $user->userSettings->phone; ?>" class="link link--block link--phone"><?= $user->userSettings->phone; ?></a>
                        </li>
                    <?php endif ?>
                    <li class="enumeration-item">
                        <a href="mailto:<?= $user->email; ?>" class="link link--block link--email"><?= $user->email; ?></a>
                    </li>
                    <?php if ($user->userSettings->tg): ?>
                        <li class="enumeration-item">
                            <a href="https://t.me/<?= $user->userSettings->tg; ?>"
                               class="link link--block link--tg">@<?= $user->userSettings->tg; ?></a>
                        </li>
                    <?php endif ?>
                </ul>
            </div>
        <?php endif ?>
    </div>
</main>