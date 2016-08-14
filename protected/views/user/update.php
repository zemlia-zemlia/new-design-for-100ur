<?php
/* @var $this UserController */
/* @var $model User */
$this->pageTitle = 'Редактирование профиля пользователя ' . CHtml::encode($model->name . ' ' . $model->lastName) . '. ' . Yii::app()->name;

$this->breadcrumbs=array(
	CHtml::encode($model->name . ' ' . $model->lastName) =>  array('/user/profile'),
	'Редактирование',
);

$this->widget('zii.widgets.CBreadcrumbs', array(
    'homeLink'=>CHtml::link('100 Юристов',"/"),
    'separator'=>' / ',
    'links'=>$this->breadcrumbs,
 ));
?>

<div class="panel panel-default">
    <div class="panel-body">
        <h1>Редактирование профиля</h1>

        <?php echo $this->renderPartial('_form', array(
                'model'             =>  $model,
                'rolesNames'        =>  $rolesNames,
                'allManagersNames'  =>  $allManagersNames,
                'yuristSettings'    =>  $yuristSettings,
                'townsArray'        =>  $townsArray,
            )); ?>

    </div>
</div>