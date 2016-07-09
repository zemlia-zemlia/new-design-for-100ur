<?php
/* @var $this UserController */
/* @var $model User */

$this->breadcrumbs=array(
	'Регистрация',
);

$this->setPageTitle("Регистрация нового пользователя. ". Yii::app()->name);
        
$this->widget('zii.widgets.CBreadcrumbs', array(
    'homeLink'=>CHtml::link('100 юристов',"/"),
    'separator'=>' / ',
    'links'=>$this->breadcrumbs,
 ));
            
?>

<div class="panel panel-default">
    <div class="panel-body">
        <h1>Регистрация нового пользователя</h1>

        <?php echo $this->renderPartial('_form', array(
            'model'             =>  $model,
            'yuristSettings'    =>  $yuristSettings,
            'townsArray'        =>  $townsArray,
            'rolesNames'        =>  $rolesNames,
        )); ?>
    </div>
</div>

