
<div class="vert-margin30">
    <h3>Осталось опубликовать вопрос</h3>
    <h4>Вопрос будет доступен юристам после подтверждения Email.<br />
        <strong>E-mail Нигде не публикуется и используется только для отправки Вам уведомлений.</strong></h4>
</div>
    <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'question-form',
        'htmlOptions'   =>  array(
            'class' =>  'center-align vert-margin20 center-block',
            'style' =>  'max-width:300px;'
        ),
        'enableAjaxValidation'  =>  false,
)); ?>
<div class="form-group">
    <?php echo $form->textField($question, 'email', array(
        'class'         =>  'form-control icon-input',
        'style'         =>  'background-image:url(/pics/2017/flying_envelop.png)',
        'data-toggle'   =>  "tooltip",
        'data-placement'=>  "right",
        'title'         =>  "Необходим для отправки Вам уведомлений о новых ответах юристов, а также является логином для входа на сайт.",
        'placeholder'=>'ivanov@mail.ru')); ?>
    <?php echo $form->error($question, 'email'); ?>
</div>

<div class="form-group">
    <?php echo CHtml::submitButton('Продолжить', array('class'=>'yellow-button center-block')); ?>
</div>
<?php $this->endWidget(); ?>
