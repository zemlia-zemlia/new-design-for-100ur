<?php
/* @var $this UserController */
/* @var $model User */

$this->breadcrumbs=array(
	'Регистрация',
);

$title = "Регистрация нового ";

if($model->role == User::ROLE_CLIENT) {
    $title .= "клиента";
} else if($model->role == User::ROLE_JURIST) {
    $title .= "юриста";
} else if($model->role == User::ROLE_BUYER) {
    $title .= "покупателя лидов";
} else {
    $title .= "клиента";
}

$this->setPageTitle($title . '. ' . Yii::app()->name);
        
$this->widget('zii.widgets.CBreadcrumbs', array(
    'homeLink'=>CHtml::link('100 Юристов',"/"),
    'separator'=>' / ',
    'links'=>$this->breadcrumbs,
 ));
            
?>


<h1><?php echo $title;?></h1>
<div class="flat-panel inside">
        

        <?php echo $this->renderPartial('_registerForm', array(
            'model'             =>  $model,
            'yuristSettings'    =>  $yuristSettings,
            'townsArray'        =>  $townsArray,
            'rolesNames'        =>  $rolesNames,
        )); ?>

</div>

