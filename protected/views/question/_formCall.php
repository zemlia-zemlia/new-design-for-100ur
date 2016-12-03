<?php $form=$this->beginWidget('CActiveForm', array(
                        'id'                    =>  'call-form',
                        'enableAjaxValidation'  =>  false,
                        'action'                =>  Yii::app()->createUrl('question/call'),
                )); ?>
                
    <div class="form-group">
            <?php echo $form->labelEx($model,'name'); ?>
            <?php echo $form->textField($model,'name', array('class'=>'form-control', 'placeholder'=>'Иванов Иван')); ?>
            <?php echo $form->error($model,'name'); ?>
    </div>

    <div class="form-group">
            <?php echo $form->labelEx($model,'phone'); ?>
            <?php echo $form->textField($model,'phone', array(
                'class'         =>  'form-control phone-mask', 
                'data-toggle'   =>  "tooltip",
                'data-placement'=>  "bottom",
                'title'         =>  "Номер телефона необходим, чтобы юрист смог с Вами связаться. Нигде не публикуется.",
                )); ?>
            <?php echo $form->error($model,'phone'); ?>
    </div>

    <div class="form-group">
        <label>Комментарий:</label>
        <?php echo $form->textArea($model,'question', array('class'=>'form-control', 'rows'=>6, 'placeholder'=>'Пожалуйста, опишите суть вопроса.')); ?>
        <?php echo $form->error($model,'question'); ?>
    </div>

    <div class="form-group">
            <?php echo $form->labelEx($model,'town'); ?>
            <?php echo CHtml::textField('town', '', array(
                'id'            =>  'town-selector', 
                'class'         =>  'form-control',
                'data-toggle'   =>  "tooltip",
                'data-placement'=>  "bottom",
                'title'         =>  "Необходим для уточнения регионального законодательства",
            )); ?>
            <?php
                echo $form->hiddenField($model, 'townId', array('id'=>'selected-town'));
            ?>
            <?php echo $form->error($model,'townId'); ?>
    </div>


    <div class="form-group" id="form-submit-wrapper">
        <?php echo CHtml::submitButton('Перезвоните мне', array('class'=>'button button-blue-gradient btn-block')); ?>
    </div>

<?php $this->endWidget(); ?>