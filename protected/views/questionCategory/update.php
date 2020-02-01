<?php
/* @var $this QuestionCategoryController */
/* @var $model QuestionCategory */

$this->setPageTitle("Редактирование категории вопросов ". $model->id . ". " . Yii::app()->name);


$this->breadcrumbs=array(
        'Вопросы и ответы'=>array('/question'),
    'Категории вопросов'=>array('index'),
    $model->name=>array('view','id'=>$model->id),
    'Редактирование',
);

$this->widget('zii.widgets.CBreadcrumbs', array(
    'homeLink'=>CHtml::link('CRM', "/"),
    'separator'=>' / ',
    'links'=>$this->breadcrumbs,
 ));

?>

<h1>Редактирование категории вопросов</h1>

<?php echo $this->renderPartial('_form', array(
        'model'=>$model,
    )); ?>