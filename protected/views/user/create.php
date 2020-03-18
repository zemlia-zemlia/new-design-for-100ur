<?php
/* @var $this UserController */
/* @var $model User */

$this->breadcrumbs = [
    'Регистрация',
];

$title = 'Регистрация нового ';

if (User::ROLE_CLIENT == $model->role) {
    $title .= 'клиента';
} elseif (User::ROLE_JURIST == $model->role) {
    $title .= 'юриста';
} elseif (User::ROLE_BUYER == $model->role) {
    $title .= 'покупателя лидов';
} elseif (User::ROLE_PARTNER == $model->role) {
    $title .= 'вебмастера';
} else {
    $title .= 'клиента';
}

$this->setPageTitle($title . '. ' . Yii::app()->name);

$this->widget('zii.widgets.CBreadcrumbs', [
    'homeLink' => CHtml::link('100 Юристов', '/'),
    'separator' => ' / ',
    'links' => $this->breadcrumbs,
 ]);

?>


<div class="flat-panel inside">
        
<h1><?php echo $title; ?></h1>

        <?php echo $this->renderPartial('_registerForm', [
            'model' => $model,
            'yuristSettings' => $yuristSettings,
            'townsArray' => $townsArray,
            'rolesNames' => $rolesNames,
        ]); ?>

</div>
