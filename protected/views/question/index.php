<?php
/* @var $this QuestionController */
/* @var $dataProvider CActiveDataProvider */

//Yii::app()->clientScript->registerLinkTag("alternate","application/rss+xml","http://".$_SERVER['SERVER_NAME'].Yii::app()->createUrl('question/rss'));
Yii::app()->clientScript->registerLinkTag("canonical",NULL,"http://".$_SERVER['SERVER_NAME'].Yii::app()->createUrl('question'));

$pageTitle = "Вопросы юристам ";

$this->setPageTitle($pageTitle . Yii::app()->name);

// для вывода облака категорий рассчитаем разницу между количеством вопросов в максимальной и минимальной категории
$deltaCounter = $counterMax - $counterMin;

$fontMax = 40; // максимальный размер шрифта в облаке
$fontMin = 11; // минимальный размер шрифта

$deltaFont = $fontMax - $fontMin;

$fontCoeff = $deltaFont/$deltaCounter;
?>


<div class="panel panel-default">
    <div class="panel-body">
		<h1><?php echo $pageTitle;?></h1>
        <?php 
            foreach($categoriesArray as $cat) {
                $fontSize = round($fontMin + $fontCoeff * $cat['counter']);
                echo CHtml::link($cat['name'], Yii::app()->createUrl('questionCategory/alias', array('name'=>$cat['alias'])), array('class'=>'cloud-category', 'style'=>'font-size:'.$fontSize.'px;'));
            }
        ?>
    </div>
</div>

