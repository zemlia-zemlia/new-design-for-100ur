<?php
/* @var $this UserStatusRequestController */
/* @var $model UserStatusRequest */

$this->breadcrumbs = [
    'Личный кабинет' => '/user',
];

$this->setPageTitle('Создание запроса на изменение статуса. ' . Yii::app()->name);

$this->widget('zii.widgets.CBreadcrumbs', [
    'homeLink' => CHtml::link('100 юристов', '/'),
    'separator' => ' / ',
    'links' => $this->breadcrumbs,
 ]);
?>

<h1 class="vert-margin30">Создание запроса на изменение статуса</h1>

<?php $this->renderPartial('_form', [
            'model' => $model,
            'userFile' => $userFile,
    ]); ?>