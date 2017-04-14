<?php
$this->setPageTitle("Сделать вопрос VIP. ". Yii::app()->name);
?>

<h1>Перевод вопроса в статус VIP</h1>
<div class="flat-panel">
    <div class='panel-body'>
        <p>
            <?php echo nl2br(mb_substr(CHtml::encode($question->questionText),0,240,'utf-8'));?>
            <?php if(strlen(CHtml::encode($question->questionText))>240) echo "..."; ?>
        </p>
    </div>
</div>

<div class="">
    <div class='panel-body center-align'>
        <h4>Оплата</h4>
        <?php $this->renderPartial('_paymentForm', array('question'=>$question));?>
    </div>
</div>