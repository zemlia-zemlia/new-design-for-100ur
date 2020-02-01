<?php
/* @var $this QuestionController */
/* @var $model Question */
/* @var $form CActiveForm */
?>

<?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'question-search',
    'action'    =>  '/question/search/',
    'method'    =>  'get',
        'htmlOptions'   =>  array(
            'class'     =>  'form-inline',
            ),
        
)); ?>

    <div class="form-group">
            <?php echo CHtml::textField('townId', $model->townName, array(
                'id'            =>  'town-selector',
                'class'         =>  'form-control',
                'placeholder'   =>  $model->getAttributeLabel('townId'),
            )); ?>
            <?php
                echo $form->hiddenField($model, 'townId', array('id'=>'selected-town'));
            ?>
            <?php echo $form->error($model, 'townId'); ?>
    </div>
    
    <div class="form-group">
            <?php echo $form->checkBox($model, 'noAnswers', array(
                'class'         =>  'form-control',
            )); ?>
            <?php echo $model->getAttributeLabel('noAnswers');?>
            <?php echo $form->error($model, 'noAnswers'); ?>
    </div>
    
    <div class="form-group">
            <?php echo $form->checkBox($model, 'today', array(
                'class'         =>  'form-control',
            )); ?>
            <?php echo $model->getAttributeLabel('today');?>
            <?php echo $form->error($model, 'today'); ?>
    </div>


    <div class="form-group">
            <?php echo CHtml::submitButton('Найти', array('class'=>'btn btn-primary')); ?>
    </div>

<?php $this->endWidget(); ?>
