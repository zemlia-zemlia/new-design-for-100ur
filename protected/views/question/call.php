<?php
$this->setPageTitle('Консультация по телефону ' . Yii::app()->name);
Yii::app()->clientScript->registerMetaTag('Заказать звонок юриста, получить консультацию по телефону', 'description');
?>


<h1 class="header-block header-block-light-grey">Запрос на консультацию по телефону</h1>


<div class='flat-panel'>
    <div class='inside'>
        <?php echo $this->renderPartial('_formCall', [
            'model' => $model,
            'townsArray' => $townsArray,
            'allDirections' => $allDirections,
        ]); ?>
    </div>
</div>
