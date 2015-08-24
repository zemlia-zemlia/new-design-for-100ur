<?php
/* @var $this QuestionController */
/* @var $model Question */

$this->setPageTitle("Вопросы юристам - новый вопрос.". Yii::app()->name);

Yii::app()->ClientScript->registerScriptFile('/js/jquery.maskedinput.min.js', CClientScript::POS_END);


$this->breadcrumbs=array(
	'Вопросы и ответы'=>array('index'),
	'Новый вопрос',
);

?>

<h1 class="vert-margin30">Задайте вопрос юристу</h1>

<div class='panel'>
    <div class='panel-body'>
        <?php echo $this->renderPartial('_form', array(
            'model'         =>  $model,
            'allCategories' =>  $allCategories,
            'categoryId'    =>  $categoryId,
            'townsArray'    =>  $townsArray,
        )); ?>
    </div>
</div>
