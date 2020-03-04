<?php
/* @var $this QuestionController */
/* @var $dataProvider CActiveDataProvider */

Yii::app()->clientScript->registerLinkTag('canonical', null, Yii::app()->createUrl('town'));

$pageTitle = 'Юристы и Адвокаты России и СНГ';

$this->setPageTitle($pageTitle . '. ' . Yii::app()->name);

// для вывода облака категорий рассчитаем разницу между количеством вопросов в максимальной и минимальной группе
$deltaCounter = $counterMax - $counterMin;

$fontMax = 40; // максимальный размер шрифта в облаке
$fontMin = 11; // минимальный размер шрифта

$deltaFont = $fontMax - $fontMin;

$fontCoeff = $deltaFont / $deltaCounter;
?>


<h1><?php echo $pageTitle; ?></h1>



        <?php
            foreach ($townsArray as $cat) {
                $fontSize = round($fontMin + $fontCoeff * $cat['counter']);
                echo CHtml::link($cat['name'], Yii::app()->createUrl('town/alias', ['name' => $cat['alias']]), ['class' => 'cloud-category', 'style' => 'font-size:' . $fontSize . 'px;']);
            }
        ?>


