<?php
/* @var $this QuestionController */
/* @var $model Question */

$this->setPageTitle("Вопросы юристам - новый вопрос.". Yii::app()->name);


$this->breadcrumbs=array(
	'Вопросы и ответы'=>array('index'),
	'Новый вопрос',
);

?>

<h1>Задайте вопрос юристу</h1>

<?php echo $this->renderPartial('_form', array(
        'model'         =>  $model,
        'allCategories' =>  $allCategories,
        'categoryId'    =>  $categoryId,
        'townsArray'    =>  $townsArray,
    )); ?>