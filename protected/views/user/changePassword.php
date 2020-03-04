<?php
/* @var $this UserController */
/* @var $model User */
$this->pageTitle = 'Изменение пароля пользователя ' . CHtml::encode($model->name . ' ' . $model->lastName) . '. ' . Yii::app()->name;

$this->breadcrumbs = [
    CHtml::encode($model->name . ' ' . $model->lastName) => ['profile'],
    'Смена пароля',
];
if (!Yii::app()->user->isGuest) {
    $this->widget('zii.widgets.CBreadcrumbs', [
        'homeLink' => CHtml::link('100 Юристов', '/'),
        'separator' => ' / ',
        'links' => $this->breadcrumbs,
    ]);
}

?>

<div class="panel panel-default">
    <div class="panel-body">
        <h1>Изменение пароля</h1>

        <?php echo $this->renderPartial('_formPassword', [
            'model' => $model,
        ]); ?>

    </div>
</div>

