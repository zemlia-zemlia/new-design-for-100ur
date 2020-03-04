<?php
/* @var $this UserController */
/* @var $model User */
$this->pageTitle = 'Изменение пароля пользователя ' . CHtml::encode($model->name . ' ' . $model->lastName) . '. ' . Yii::app()->name;

$this->breadcrumbs = [
    'Пользователи' => ['index'],
    CHtml::encode($model->name . ' ' . $model->lastName) => ['view', 'id' => $model->id],
    'Смена пароля',
];

$this->widget('zii.widgets.CBreadcrumbs', [
    'homeLink' => CHtml::link('CRM', '/'),
    'separator' => ' / ',
    'links' => $this->breadcrumbs,
 ]);
?>

<h1>Изменение пароля</h1>

<?php echo $this->renderPartial('_formPassword', [
        'model' => $model,
    ]); ?>

