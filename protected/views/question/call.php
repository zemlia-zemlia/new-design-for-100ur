<?php
$this->setPageTitle("Заказать звонок ". Yii::app()->name);
?>

<div class='panel panel-default'>
    <div class='panel-body'>
        <h1>Заказать звонок</h1>
    </div>
</div>

<div class='panel gray-panel'>
    <div class='panel-body'>
        <?php echo $this->renderPartial('_formCall', array(
            'model'         =>  $model,
            'townsArray'    =>  $townsArray,
        )); ?>
    </div>
</div>
