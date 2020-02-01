<?php
/* @var $this QuestionCategoryController */
/* @var $model QuestionCategory */

$this->setPageTitle("Создание категории вопросов. " . Yii::app()->name);


$this->breadcrumbs=array(
        'Вопросы и ответы'      =>  array('/question'),
    'Категории вопросов'    =>  array('index'),
    'Новая категория',
);
$this->widget('zii.widgets.CBreadcrumbs', array(
    'homeLink'=>CHtml::link('CRM', "/"),
    'separator'=>' / ',
    'links'=>$this->breadcrumbs,
 ));
?>

<h1>Новая категория вопросов</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>