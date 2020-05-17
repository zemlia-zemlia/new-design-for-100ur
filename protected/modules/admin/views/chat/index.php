<?php
/* @var $this ChatController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs = [
    'Чаты',
];

?>

<h1>Чаты</h1>

<div class='row'>
    <div class=col-md-4>Чаты со статусом (запрос)

            <?php foreach ($notConfirmeds as $chat) : ?>
                 <?php $this->renderPartial('_view', ['model' => $chat]); ?>
            <?php endforeach; ?>


    </div>
    <div class=col-md-4>Чаты со статусом (Открыт)

            <?php foreach ($confirmeds as $chat) : ?>
                <?php $this->renderPartial('_view', ['model' => $chat]); ?>
            <?php endforeach; ?>

    </div>
    <div class=col-md-4>Чаты со статусом (Закрыт)

            <?php foreach ($closeds as $chat) : ?>
                <?php $this->renderPartial('_view', ['model' => $chat]); ?>
            <?php endforeach; ?>

    </div>
    <div class=col-md-4>Чаты со статусом (Конфликт)

            <?php foreach ($petitions as $chat) : ?>
                <?php $this->renderPartial('_view', ['model' => $chat]); ?>
            <?php endforeach; ?>

    </div>
</div>
