<?php
/* @var $this PostController */
/* @var $model Post */

$this->setPageTitle("Редактирование поста " . CHtml::encode($model->title) . " | Публикации" . " | ". Yii::app()->name);

$this->breadcrumbs=array(
	'Блог'    =>  array('/blog'),
	CHtml::encode($model->title)    =>  array('view','id'=>$model->id),
	'Редактирование',
);
?>

<?php
    $this->widget('zii.widgets.CBreadcrumbs', array(
        'homeLink'=>CHtml::link('CRM',"/"),
        'separator'=>' / ',
        'links'=>$this->breadcrumbs,
     ));
?>

<h1>Редактирование поста</h1>

<?php echo $this->renderPartial('_form', array(
        'model'             =>  $model,
        'categoriesArray'   =>  $categoriesArray,
    )); ?>