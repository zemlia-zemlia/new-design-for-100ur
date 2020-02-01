<?php
/* @var $this QuestionController */
/* @var $model Question */

$this->setPageTitle("Вопросы и ответы - новый вопрос.". Yii::app()->name);


$this->breadcrumbs=array(
    'Вопросы и ответы'=>array('index'),
    'Новый вопрос',
);
$this->widget('zii.widgets.CBreadcrumbs', array(
    'homeLink'=>CHtml::link('CRM', "/"),
    'separator'=>' / ',
    'links'=>$this->breadcrumbs,
 ));
?>

<h1>Добавление вопроса</h1>

<?php echo $this->renderPartial('_form', array(
        'model'         =>  $model,
        'allCategories' =>  $allCategories,
        'categoryId'    =>  $categoryId,
        'townsArray'    =>  $townsArray,
    )); ?>