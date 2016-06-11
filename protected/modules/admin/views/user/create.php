<?php
/* @var $this UserController */
/* @var $model User */

$this->breadcrumbs=array(
	'Пользователи'=>array('index'),
	'Регистрация',
);

$this->setPageTitle("Регистрация нового пользователя. ". Yii::app()->name);
        
$this->widget('zii.widgets.CBreadcrumbs', array(
    'homeLink'=>CHtml::link('CRM',"/"),
    'separator'=>' / ',
    'links'=>$this->breadcrumbs,
 ));
            
?>

<h1>Регистрация нового пользователя</h1>

<?php echo $this->renderPartial('_form', array(
        'model'             =>  $model,
        'rolesNames'        =>  $rolesNames,
        'allManagersNames'  =>  $allManagersNames,
        'yuristSettings'    =>  $yuristSettings,
        'townsArray'        =>  $townsArray,
    )); ?>