<?php
/* @var $this TownController */
/* @var $model Town */

$this->pageTitle = "Редактирование города " . CHtml::encode($model->name) . '. ' . Yii::app()->name;


$this->breadcrumbs=array(
	'Регионы'=>array('/admin/region'),
	CHtml::encode($model->region->name)=>array('/admin/region/view', 'regionAlias'=>CHtml::encode($model->region->alias)),
        CHtml::encode($model->name)=>array('/admin/town/view', 'id'=>$model->id),
        'Редактирование',
);

$this->widget('zii.widgets.CBreadcrumbs', array(
    'homeLink'=>CHtml::link('100 юристов',"/"),
    'separator'=>' / ',
    'links'=>$this->breadcrumbs,
 ));

?>

<h1>Редактирование города <?php echo CHtml::encode($model->name); ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>