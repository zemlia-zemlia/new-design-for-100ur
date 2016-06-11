<?php
/* @var $this UserController */
/* @var $model User */
$this->pageTitle = 'Редактирование профиля пользователя ' . CHtml::encode($model->name . ' ' . $model->lastName) . '. ' . Yii::app()->name;

$this->breadcrumbs=array(
	'Пользователи'              =>  array('index'),
	CHtml::encode($model->name . ' ' . $model->lastName) =>  array('view','id'=>$model->id),
	'Редактирование',
);

$this->widget('zii.widgets.CBreadcrumbs', array(
    'homeLink'=>CHtml::link('CRM',"/"),
    'separator'=>' / ',
    'links'=>$this->breadcrumbs,
 ));
?>

<h1>Редактирование профиля</h1>

<?php echo $this->renderPartial('_form', array(
        'model'             =>  $model,
        'rolesNames'        =>  $rolesNames,
        'allManagersNames'  =>  $allManagersNames,
        'yuristSettings'    =>  $yuristSettings,
        'townsArray'        =>  $townsArray,
    )); ?>