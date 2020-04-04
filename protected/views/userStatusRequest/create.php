<?php
/* @var $this UserStatusRequestController */

use App\models\UserStatusRequest;

/* @var $model UserStatusRequest */

$this->breadcrumbs = [
    'Личный кабинет' => '/user',
];

$this->setPageTitle('Подтверждение квалификации. ' . Yii::app()->name);

$this->widget('zii.widgets.CBreadcrumbs', [
    'homeLink' => CHtml::link('100 юристов', '/'),
    'separator' => ' / ',
    'links' => $this->breadcrumbs,
 ]);
?>

<h1 class="vert-margin30">Подтверждение квалификации</h1>

<?php $this->renderPartial('_form', [
        'model' => $model,
        'userFile' => $userFile,
        'currentUser' => $currentUser,
    ]); ?>