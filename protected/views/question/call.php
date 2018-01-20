<?php
$this->setPageTitle("Заказать звонок ". Yii::app()->name);
Yii::app()->clientScript->registerMetaTag("Заказать звонок юриста, получить консультацию по телефону", 'description');
?>


<h1 class="header-block header-block-light-grey">Заказать звонок</h1>


<div class='flat-panel'>
    <div class='inside'>
        <?php echo $this->renderPartial('_formCall', array(
            'model'         =>  $model,
            'townsArray'    =>  $townsArray,
            'allDirections' =>  $allDirections,
        )); ?>
    </div>
</div>
