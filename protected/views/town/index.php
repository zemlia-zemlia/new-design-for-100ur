<?php
/* @var $this QuestionController */
/* @var $dataProvider CActiveDataProvider */

Yii::app()->clientScript->registerLinkTag("canonical",NULL,"http://".$_SERVER['SERVER_NAME'].Yii::app()->createUrl('town'));

$pageTitle = "Консультации юриста в городах России";

$this->setPageTitle($pageTitle . '. ' . Yii::app()->name);

// для вывода облака категорий рассчитаем разницу между количеством вопросов в максимальной и минимальной группе
$deltaCounter = $counterMax - $counterMin;

$fontMax = 40; // максимальный размер шрифта в облаке
$fontMin = 11; // минимальный размер шрифта

$deltaFont = $fontMax - $fontMin;

$fontCoeff = $deltaFont/$deltaCounter;
?>

<div class="panel panel-default">
    <div class="panel-body">
		<h1><?php echo $pageTitle;?></h1>
	</div>
</div>

<div class="panel panel-default">
    <div class="panel-body">
        <?php 
            foreach($townsArray as $cat) {
                $fontSize = round($fontMin + $fontCoeff * $cat['counter']);
                echo CHtml::link($cat['name'], Yii::app()->createUrl('town/alias', array('name'=>$cat['alias'])), array('class'=>'cloud-category', 'style'=>'font-size:'.$fontSize.'px;'));
            }
        ?>
    </div>
</div>

