<?php

    $services = array(
        'Защита в суде'                     =>  'Защита в суде',
        'Представление интересов'           =>  'Представление интересов',
        'Сопровождение сделки'              =>  'Сопровождение сделки',
        'Юридическая экспертиза документов' =>  'Юридическая экспертиза документов',
    );

?>

<?php $form=$this->beginWidget('CActiveForm', array(
                        'id'                    =>  'services-form',
                        'enableAjaxValidation'  =>  false,
                        'action'                =>  Yii::app()->createUrl('question/services'),
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
        <label>Вид услуги:</label>
        <?php echo $form->dropDownList($model,'question', $services, array('class'=>'form-control')); ?>
        <?php echo $form->error($model,'question'); ?>
    </div>

    <div class="form-group">
            <?php
                $currenTownName = (Yii::app()->user->getState('currentTownName'))?Yii::app()->user->getState('currentTownName'):'';
                $currentRegionName = (Yii::app()->user->getState('currentTownRegionName'))?Yii::app()->user->getState('currentTownRegionName'):'';
                $currenTownId = (Yii::app()->user->getState('currentTownId'))?Yii::app()->user->getState('currentTownId'):0;
            ?>
        <?php if($currenTownId == 0):?> 
            <?php echo $form->labelEx($model,'town'); ?>
            <?php echo CHtml::textField('town', $currenTownName, array(
                'id'            =>  'town-selector', 
                'class'         =>  'form-control',
                'data-toggle'   =>  "tooltip",
                'data-placement'=>  "bottom",
                'title'         =>  "Необходим для уточнения регионального законодательства",
            )); ?>
        <?php endif;?>
        
            <?php
                echo $form->hiddenField($model, 'townId', array('id'=>'selected-town', 'value'=>$currenTownId));
            ?>
            <?php echo $form->error($model,'townId'); ?>
    </div>


    <div class="form-group" id="form-submit-wrapper">
        <?php echo CHtml::submitButton('Перезвоните мне', array('class'=>'button button-blue-gradient btn-block')); ?>
    </div>

<?php $this->endWidget(); ?>