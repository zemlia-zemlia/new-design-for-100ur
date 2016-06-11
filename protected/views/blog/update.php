<?php
/* @var $this CategoryController */
/* @var $model Postcategory */

$this->setPageTitle("Редактирование категории " . CHtml::encode($model->title) . " | Публикации" . " | ". Yii::app()->name);

$this->breadcrumbs=array(
	'Публикации'    =>  array('/category'),
	CHtml::encode($model->title)    =>  array('view','id'=>$model->id),
	'Редактирование',
);
?>

<?php
    $this->widget('zii.widgets.CBreadcrumbs', array(
        'homeLink'=>CHtml::link('Консультация юриста',"/"),
        'separator'=>' / ',
        'links'=>$this->breadcrumbs,
     ));
?>


<h1>Редактирование категории</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>