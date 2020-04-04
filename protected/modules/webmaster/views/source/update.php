<?php
/* @var $this LeadsourceController */

use App\models\Leadsource;

/* @var $model Leadsource */

$this->pageTitle = 'Редактирование источника контактов. ' . Yii::app()->name;

$this->breadcrumbs = [
    'Источники контактов' => ['index'],
    $model->name => ['view', 'id' => $model->id],
    'Редактирование',
];

$this->widget('zii.widgets.CBreadcrumbs', [
    'homeLink' => CHtml::link('100 юристов', '/'),
    'separator' => ' / ',
    'links' => $this->breadcrumbs,
 ]);
?>

<h1>Редактирование источника контактов</h1>

<?php echo $this->renderPartial('_form', ['model' => $model]); ?>