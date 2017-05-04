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


<h1>Регистрация нового пользователя</h1>
<div class="flat-panel inside">
        

        <?php echo $this->renderPartial('_registerForm', array(
            'model'             =>  $model,
            'yuristSettings'    =>  $yuristSettings,
            'townsArray'        =>  $townsArray,
            'rolesNames'        =>  $rolesNames,
        )); ?>

</div>

