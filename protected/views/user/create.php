<?php
/* @var $this UserController */
/* @var $model User */

$this->breadcrumbs=array(
    'Регистрация',
);

$title = "Регистрация нового ";

if ($model->role == User::ROLE_CLIENT) {
    $title .= "клиента";
} elseif ($model->role == User::ROLE_JURIST) {
    $title .= "юриста";
} elseif ($model->role == User::ROLE_BUYER) {
    $title .= "покупателя лидов";
} elseif ($model->role == User::ROLE_PARTNER) {
    $title .= "вебмастера";
} else {
    $title .= "клиента";
}

$this->setPageTitle($title . '. ' . Yii::app()->name);
        
$this->widget('zii.widgets.CBreadcrumbs', array(
    'homeLink'=>CHtml::link('100 Юристов', "/"),
    'separator'=>' / ',
    'links'=>$this->breadcrumbs,
 ));
            
?>


<div class="flat-panel inside">
        
<h1><?php echo $title;?></h1>

        <?php echo $this->renderPartial('_registerForm', array(
            'model'             =>  $model,
            'yuristSettings'    =>  $yuristSettings,
            'townsArray'        =>  $townsArray,
            'rolesNames'        =>  $rolesNames,
        )); ?>

</div>

