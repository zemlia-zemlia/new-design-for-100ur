<?php
/* @var $this TownController */

use App\models\Town;

/* @var $model Town */

$this->pageTitle = 'Редактирование города ' . CHtml::encode($model->name) . '. ' . Yii::app()->name;

$this->breadcrumbs = [
    'Регионы' => ['/admin/region'],
    CHtml::encode($model->region->name) => ['/admin/region/view', 'regionAlias' => CHtml::encode($model->region->alias)],
        CHtml::encode($model->name) => ['/admin/town/view', 'id' => $model->id],
        'Редактирование',
];

$this->widget('zii.widgets.CBreadcrumbs', [
    'homeLink' => CHtml::link('100 юристов', '/'),
    'separator' => ' / ',
    'links' => $this->breadcrumbs,
 ]);

?>

<h1>Редактирование города <?php echo CHtml::encode($model->name); ?></h1>

<?php echo $this->renderPartial('_form', ['model' => $model]); ?>