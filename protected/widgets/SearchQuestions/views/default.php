<?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'question-search',
	'action'    =>  '/question/search/',
	'method'    =>  'get',
        'htmlOptions'   =>  array(
            'class'     =>  '',
            ),
        
)); ?>

<?php
    $counterNoAnswers = Question::getCountWithoutAnswers();
?>

<h4 class="widget-search-header">
    <span>
    <?php echo CHtml::link($counterNoAnswers, Yii::app()->createUrl('/question/search')); ?>
    </span>
    <?php echo CHtml::link(CustomFuncs::numForms($counterNoAnswers, 'вопрос', "вопроса", "вопросов") . ' ждут <br />ответов', Yii::app()->createUrl('/question/search')); ?>
</h4>

<?php if($model->townId):?>
    <div class="checkbox">
        <label>
            <?php echo CHtml::checkBox('', ''); ?>
            <?php echo CHtml::link('Из моего города', Yii::app()->createUrl('/question/search/?QuestionSearch[townId]=' . $model->townId));?>
        </label>
    </div>
<?php endif;?>


    <div class="checkbox">
        <label>
            <?php echo $form->checkBox($model, 'noAnswers', array(
            )); ?>
            <?php echo $model->getAttributeLabel('noAnswers');?>
        </label>
            <?php echo $form->error($model,'noAnswers'); ?>
    </div>
    
    <div class="checkbox">
        <label>
            <?php echo $form->checkBox($model, 'today', array(
            )); ?>
            <?php echo $model->getAttributeLabel('today');?>
        </label>
            <?php echo $form->error($model,'today'); ?>
    </div>


    <div class="form-group">
            <?php echo CHtml::submitButton('Найти', array('class'=>'button button-blue-gradient btn-block')); ?>
    </div>

<?php $this->endWidget(); ?>
