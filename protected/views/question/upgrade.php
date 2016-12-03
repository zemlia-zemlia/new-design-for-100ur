<?php
$this->setPageTitle("Сделать вопрос VIP. ". Yii::app()->name);
?>


<div class="panel gray-panel">
    <div class='panel-body'>
        <h1>Сделать вопрос VIP</h1>
    </div>
</div>

<div class="panel gray-panel">
    <div class='panel-body'>
        <h4>Текст вопроса</h4>
        <p>
            <?php echo nl2br(mb_substr(CHtml::encode($question->questionText),0,240,'utf-8'));?>
            <?php if(strlen(CHtml::encode($question->questionText))>240) echo "..."; ?>
        </p>
    </div>
</div>

<div class="panel gray-panel">
    <div class='panel-body center-align'>
        <h4>Оплатить вопрос</h4>
        <?php $this->renderPartial('_paymentForm', array('question'=>$question));?>
    </div>
</div>