<?php
/* @var $this CodecsController */
/* @var $dataProvider CActiveDataProvider */

$this->setPageTitle("Законы и кодексы Российской Федерации. ". Yii::app()->name);

Yii::app()->clientScript->registerMetaTag("Законы и кодексы Российской Федерации", 'description');

?>

<h1 class="header-block header-block-light-grey vert-margin30">Кодексы РФ</h1>



        <?php foreach($codecsArray as $codecs):?>

        <p>
            <?php echo CHtml::link($codecs->pagetitle, Yii::app()->createUrl('/codecs/'.$codecs->alias));?>
        </p>

        <?php endforeach; ?>
