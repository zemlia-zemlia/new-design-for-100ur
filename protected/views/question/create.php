<?php
/* @var $this QuestionController */
/* @var $model Question */

$this->setPageTitle("Задать вопрос юристам и адвокатам. ". Yii::app()->name);

Yii::app()->ClientScript->registerScriptFile('/js/jquery.maskedinput.min.js', CClientScript::POS_END);


$this->breadcrumbs=array(
	'Вопросы и ответы'=>array('index'),
	'Новый вопрос',
);

?>
<div class='panel gray-panel'>
    <div class='panel-body'>
		<h1>Задайте вопрос юристу</h1>
    </div>
</div>
<div class='panel gray-panel'>
    <div class='panel-body'>
        <?php echo $this->renderPartial('_form', array(
            'model'         =>  $model,
            'allCategories' =>  $allCategories,
            'categoryId'    =>  $categoryId,
            'townsArray'    =>  $townsArray,
        )); ?>
    </div>
</div>
