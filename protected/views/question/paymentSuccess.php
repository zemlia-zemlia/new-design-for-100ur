<?php
    $this->setPageTitle("Платеж успешно совершен. " . Yii::app()->name);
?>

<div class="panel gray-panel">
    <div class='panel-body'>
        <h1>Платеж успешно совершен</h1>
        <p class="center-align">
        <?php
            if ($params['customerNumber']) {
                echo CHtml::link('На страницу вопроса', Yii::app()->createUrl('question/view', array('id'=>(int)$params['customerNumber'])), array('class'=>'btn btn-primary'));
            }
        ?>
        </p>
    </div>
</div>

