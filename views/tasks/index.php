<?php

use yii\helpers\ArrayHelper;
use yii\helpers\BaseStringHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$tasks = $tasks ?? [];
$categories = $categories ?? [];
?>
<main class="main-content container">
    <div class="left-column">
        <h3 class="head-main head-task">Новые задания</h3>
        <?php foreach ($tasks as $task): ?>
            <div class="task-card">
                <div class="header-task">
                    <a  href="#" class="link link--block link--big"><?= Html::encode($task->name)  ?></a>
                    <p class="price price--task"><?= $task->budget ?></p>
                </div>
                <p class="info-text"><span class="current-time"><?= Yii::$app->formatter->asRelativeTime($task->dt_add) ?> </span></p>
                <p class="task-text"> <?= Html::encode(BaseStringHelper::truncate($task->description, 200) )  ?></p>
                <div class="footer-task">
                    <p class="info-text town-text"><?= $task->location ?></p>
                    <p class="info-text category-text"> <?= $task->category->name ?></p>
                    <a href="#" class="button button--black">Смотреть Задание</a>
                </div>
            </div>
        <?php endforeach; ?>

        <div class="pagination-wrapper">
            <ul class="pagination-list">
                <li class="pagination-item mark">
                    <a href="#" class="link link--page"></a>
                </li>
                <li class="pagination-item">
                    <a href="#" class="link link--page">1</a>
                </li>
                <li class="pagination-item pagination-item--active">
                    <a href="#" class="link link--page">2</a>
                </li>
                <li class="pagination-item">
                    <a href="#" class="link link--page">3</a>
                </li>
                <li class="pagination-item mark">
                    <a href="#" class="link link--page"></a>
                </li>
            </ul>
        </div>
    </div>
    <div class="right-column">
        <div class="right-card black">
            <div class="search-form">
                <?php $form = ActiveForm::begin(); ?>
                    <h4 class="head-card">Категории</h4>
                    <?= Html::activeCheckboxList($tasksModel, 'category_id', array_column($categories, 'name', 'id'),
                        [
                            'class' => 'checkbox-wrapper',
                            'itemOptions' => ['labelOptions' => ['class' => 'control-label']],
//                            'tag' => false
                        ]
                    ); ?>
                    <h4 class="head-card">Дополнительно</h4>
                    <div class="form-group">
                        <?= $form->field($tasksModel, 'noLocation')->checkbox(['labelOptions' => ['class' => 'control-label']]) ?>
                        <?= $form->field($tasksModel, 'noResponse')->checkbox(['labelOptions' => ['class' => 'control-label']]) ?>
                    </div>
                    <h4 class="head-card">Период</h4>
                    <div class="form-group">
                        <?= $form->field($tasksModel, 'filterPeriod', ['template' => '{input}'])->dropDownList([
                            '3600' => 'За последний час',
                            '86400' => 'За сутки',
                            '604800' => 'За неделю'
                        ], ['prompt' => 'Выбрать']); ?>
                    </div>
                    <input type="submit" class="button button--blue" value="Искать">
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</main>