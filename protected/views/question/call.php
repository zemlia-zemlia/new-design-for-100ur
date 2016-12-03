<?php
$this->setPageTitle("Заказать звонок ". Yii::app()->name);
?>


<h1 class="header-block header-block-light-grey">Заказать звонок</h1>


<div class='flat-panel'>
    <div class='inside'>
        <?php echo $this->renderPartial('_formCall', array(
            'model'         =>  $model,
            'townsArray'    =>  $townsArray,
        )); ?>
    </div>
</div>
