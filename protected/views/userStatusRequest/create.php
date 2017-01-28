<?php
/* @var $this UserStatusRequestController */
/* @var $model UserStatusRequest */

$this->breadcrumbs=array(
	'Личный кабинет'    =>  '/user',
);

$this->setPageTitle("Создание запроса на изменение статуса. ". Yii::app()->name);
        
$this->widget('zii.widgets.CBreadcrumbs', array(
    'homeLink'=>CHtml::link('100 юристов',"/"),
    'separator'=>' / ',
    'links'=>$this->breadcrumbs,
 ));
?>

<h1 class="vert-margin30">Создание запроса на изменение статуса</h1>

<?php $this->renderPartial('_form', array(
        'model'=>$model,
        'userFile'  =>  $userFile,
        'currentUser'   =>  $currentUser,
    )); ?>