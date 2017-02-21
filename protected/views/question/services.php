<?php
$this->setPageTitle("Заказать юридические услуги ". Yii::app()->name);
?>


<h1 class="header-block header-block-light-grey">Заказать юридические услуги</h1>


<div class='flat-panel'>
    <div class='inside'>
        <?php echo $this->renderPartial('_formServices', array(
            'model'         =>  $model,
            'townsArray'    =>  $townsArray,
        )); ?>
    </div>
</div>
