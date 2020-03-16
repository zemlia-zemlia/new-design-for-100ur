<?php
/* @var $this LeadsourceController */

use App\models\Leadsource;

/* @var $model Leadsource */

$this->pageTitle = 'Источники контактов. ' . Yii::app()->name;

$this->breadcrumbs = [
    'Источники контактов' => ['index'],
    'Новый',
];
$this->widget('zii.widgets.CBreadcrumbs', [
    'homeLink' => CHtml::link('100 юристов', '/'),
    'separator' => ' / ',
    'links' => $this->breadcrumbs,
 ]);
?>

<h1>Новый источник контактов</h1>

<?php echo $this->renderPartial('_form', ['model' => $model]); ?>