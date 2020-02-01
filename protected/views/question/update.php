<?php
/* @var $this QuestionController */
/* @var $model Question */

$this->setPageTitle("Вопросы и ответы - редактировать вопрос. ". Yii::app()->name);

$this->breadcrumbs=array(
    'Вопросы и ответы'=>array('index'),
    $model->id=>array('view','id'=>$model->id),
    'Редактирование',
);

$this->widget('zii.widgets.CBreadcrumbs', array(
    'homeLink'=>CHtml::link('CRM', "/"),
    'separator'=>' / ',
    'links'=>$this->breadcrumbs,
 ));

?>

<h1>Редактирование вопроса <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array(
        'model'         =>  $model,
        'allCategories' =>  $allCategories,
        'townsArray'    =>  $townsArray,
    )); ?>