<?php
/* @var $this CodecsController */
/* @var $dataProvider CActiveDataProvider */

$this->setPageTitle("Законы и кодексы Российской Федерации. ". Yii::app()->name);

Yii::app()->clientScript->registerMetaTag("Законы и кодексы Российской Федерации", 'description');

?>
<div class="panel panel-default">
    <div class='panel-body'>
        <h1>Кодексы РФ</h1>
    </div>
</div>

<div class="panel panel-default">
    <div class='panel-body'>
        <?php foreach($codecsArray as $codecs):?>

        <p>
            <?php echo CHtml::link($codecs->pagetitle, Yii::app()->createUrl('/codecs/'.$codecs->alias));?>
        </p>

        <?php endforeach; ?>

    </div>
</div>