<?php
/* @var $this PostController */
/* @var $model Post */

$this->setPageTitle("Новый пост" . " | Публикации" . " | ". Yii::app()->name);

$this->breadcrumbs=array(
	'Блог'=>array('/admin/blog'),
	'Новый пост',
);
?>

<?php
    $this->widget('zii.widgets.CBreadcrumbs', array(
        'homeLink'=>CHtml::link('100 Юристов',"/admin/"),
        'separator'=>' / ',
        'links'=>$this->breadcrumbs,
     ));
?>

<h1>Новый пост</h1>

<?php echo $this->renderPartial('_form', array(
        'model'             =>  $model,
        'categoriesArray'   =>  $categoriesArray,
    )); 
?>