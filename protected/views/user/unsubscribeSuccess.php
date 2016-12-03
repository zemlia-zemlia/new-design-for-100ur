<?php
$this->setPageTitle('Вы успешно отписались от почтовой рассылки. ' . Yii::app()->name);

?>

<div class="panel panel-default gray-panel">
    <div class="panel-body">
        <h1>Вы успешно отписались от почтовой рассылки</h1>
        <p class="center-align">
            <?php echo CHtml::link("На главную страницу", Yii::app()->createUrl('/'), array('class'=>'btn btn-primary'));?>
        </p>
    </div>
</div>