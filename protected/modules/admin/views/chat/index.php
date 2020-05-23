<?php
/* @var $this ChatController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs = array(
    'Чаты',
);

$this->pageTitle = 'Чаты';
Yii::app()->clientScript->registerScriptFile('/js/admin/region.js');
?>

<?php
$this->widget('zii.widgets.CBreadcrumbs', [
    'homeLink' => CHtml::link('100 Юристов', '/'),
    'separator' => ' / ',
    'links' => $this->breadcrumbs,
]);

?>

<h1 class="vert-margin40">Чаты</h1>

<div class='row'>
    <div class=col-md-3>
        <div class="box">
            <div class="box-body">
                <h4 class="vert-margin40">Отправлен запрос</h4>
                <?php foreach ($notConfirmeds as $chat) : ?>
                    <?php $this->renderPartial('_view', ['model' => $chat]); ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <div class=col-md-3>
        <div class="box">
            <div class="box-body">
                <h4 class="vert-margin40">Ожидают оплаты</h4>
                <?php foreach ($notPayed as $chat) : ?>
                    <?php $this->renderPartial('_view', ['model' => $chat]); ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <div class=col-md-3>
        <div class="box">
            <div class="box-body">
                <h4 class="vert-margin40">Открыты</h4>
                <?php foreach ($confirmeds as $chat) : ?>
                    <?php $this->renderPartial('_view', ['model' => $chat]); ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <div class=col-md-3>
        <div class="box">
            <div class="box-body">
                <h4 class="vert-margin40">Конфликт</h4>
                <?php foreach ($petitions as $chat) : ?>
                    <?php $this->renderPartial('_view', ['model' => $chat]); ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<hr>

<div class="row">
    <div class="col-md-3">
        <div class="box">
            <div class="box-body">
                <h4 class="vert-margin40">Закрыт</h4>
                <?php foreach ($closeds as $chat) : ?>
                    <?php $this->renderPartial('_view', ['model' => $chat]); ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>