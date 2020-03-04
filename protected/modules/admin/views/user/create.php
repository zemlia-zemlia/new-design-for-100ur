<?php
/* @var $this UserController */
/* @var $model User */

$this->breadcrumbs = [
    'Пользователи' => ['index'],
    'Регистрация',
];

$this->setPageTitle('Регистрация нового пользователя. ' . Yii::app()->name);

$this->widget('zii.widgets.CBreadcrumbs', [
    'homeLink' => CHtml::link('CRM', '/'),
    'separator' => ' / ',
    'links' => $this->breadcrumbs,
 ]);

?>

<h1>Регистрация нового пользователя</h1>

<?php echo $this->renderPartial('_form', [
        'model' => $model,
        'townsArray' => $townsArray,
        'yuristSettings' => $yuristSettings,
        'rolesNames' => $rolesNames,
        'allDirections' => $allDirections,
    ]); ?>