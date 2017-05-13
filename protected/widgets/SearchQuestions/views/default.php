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

<h4 class="header-block header-block-light-grey" >Фильтр вопросов:</h4>

<div class="flat-panel inside">
<h4 class="widget-search-header">
    <span>
    <?php echo CHtml::link($counterNoAnswers, Yii::app()->createUrl('/question/search')); ?>
    </span>
    <?php echo CHtml::link(CustomFuncs::numForms($counterNoAnswers, 'вопрос', "вопроса", "вопросов") . ' ждут <br />ответов', Yii::app()->createUrl('/question/search')); ?>
</h4>

    <div class="checkbox">
        <label data-toggle="tooltip" data-placement ="left" title="Укажите свои специальноси в профиле">
            <?php echo $form->checkBox($model, 'myCats', array(
            )); ?>
            <?php echo $model->getAttributeLabel('myCats');?>
        </label>
            <?php echo $form->error($model,'myCats'); ?>
    </div>

    <div class="checkbox">
        <label>
            <?php echo $form->checkBox($model, 'myTown', array(
            )); ?>
            <?php echo $model->getAttributeLabel('myTown');?>
        </label>
            <?php echo $form->error($model,'myTown'); ?>
    </div>

    <div class="checkbox">
        <label>
            <?php echo $form->checkBox($model, 'sameRegion', array(
            )); ?>
            <?php echo $model->getAttributeLabel('sameRegion');?>
        </label>
            <?php echo $form->error($model,'sameRegion'); ?>
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
            <?php echo CHtml::submitButton('Показать', array('class'=>'button button-blue-gradient btn-block')); ?>
            
            <?php if($randomQuestionId):?>
                <?php echo CHtml::link('Случайный вопрос', Yii::app()->createUrl('question/view', array('id'=>$randomQuestionId)), array('class'=>'button btn-block button-blue-gradient')); ?>
            <?php endif;?>
    </div>
</div>
<?php $this->endWidget(); ?>
