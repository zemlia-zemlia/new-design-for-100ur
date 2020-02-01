<?php
/* @var $this QuestionController */
/* @var $model Question */

$this->setPageTitle("Редактирование ответа. ". Yii::app()->name);
Yii::app()->clientScript->registerScriptFile('/js/answer.js');

$this->breadcrumbs=array(
    'Ответы'=>array('index'),
    $model->id=>array('view','id'=>$model->id),
    'Редактирование',
);

$this->widget('zii.widgets.CBreadcrumbs', array(
    'homeLink'=>CHtml::link('CRM', "/"),
    'separator'=>' / ',
    'links'=>$this->breadcrumbs,
 ));

?>

<h1>Редактирование ответа <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array(
        'model'         =>  $model,
    )); ?>