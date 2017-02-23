<?php
/* @var $this CategoryController */
/* @var $model Postcategory */

$this->breadcrumbs=array(
	'Блог'=>array('index'),
	'Новая категория',
);
$this->setPageTitle("Создание категории публикаций" . " | ". Yii::app()->name);

?>

<?php
    $this->widget('zii.widgets.CBreadcrumbs', array(
        'homeLink'=>CHtml::link('Консультация юриста',"/"),
        'separator'=>' / ',
        'links'=>$this->breadcrumbs,
     ));
?>

<h1>Новая категория публикаций</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>