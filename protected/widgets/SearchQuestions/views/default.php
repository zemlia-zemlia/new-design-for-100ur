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

<h4 class="header-block header-block-light-grey" >Поиск вопросов:</h4>

<div class="flat-panel inside">
    <div class="row">
        <div class="col-xs-4">
            <h4 class="widget-search-header">
                <span>
                <?php echo $counterNoAnswers; ?>
                </span>
            </h4>
        </div>
        <div class="col-xs-8">
            <?php echo CustomFuncs::numForms($counterNoAnswers, 'вопрос', "вопроса", "вопросов") . ' без ответов'; ?>
            <br />
            <?php echo CHtml::link("показать", Yii::app()->createUrl('/question/search'), ['class'=>'yellow-button arrow']); ?>
        </div>
    </div>
    <hr />

    <h4>Отфильтровать по параметрам</h4>
    
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
            <?php echo CHtml::submitButton('Отфильтровать', array('class'=>'yellow-button btn-block')); ?>
            
            <?php if($randomQuestionId):?>
                <?php echo CHtml::link('Показать случайный вопрос', Yii::app()->createUrl('question/view', array('id'=>$randomQuestionId)), array('class'=>'button btn-block button-blue-gradient')); ?>
            <?php endif;?>
    </div>
</div>
<?php $this->endWidget(); ?>
