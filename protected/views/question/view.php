<?php
/* @var $this QuestionController */
/* @var $model Question */

$this->setPageTitle(CHtml::encode($model->id) . ". Вопросы-ответы. ". Yii::app()->name);

$this->breadcrumbs=array(
	'Вопросы'=>array('index'),
	$model->id,
);

$this->widget('zii.widgets.CBreadcrumbs', array(
    'homeLink'=>CHtml::link('CRM',"/"),
    'separator'=>' / ',
    'links'=>$this->breadcrumbs,
 ));

?>
<div class="vert-margin30">
<h1>Вопрос #<?php echo $model->id; ?></h1>
<?php if($model->title):?>
    <h3><?php echo CHtml::encode($model->title);?> </h3>
<?php endif;?>
</div>

<p>
    <strong><?php echo $model->getAttributeLabel('category');?>:</strong>
    <?php echo CHtml::link(CHtml::encode($model->category->name),Yii::app()->createUrl('questionCategory/view',array('id'=>$model->category->id)));?>
</p>
<div class="vert-margin30">
<p>
    <?php echo nl2br(CHtml::encode($model->questionText));?>
</p>
</div>

<div class="vert-margin30">
    <p><strong>Статус:</strong> <?php echo CHtml::encode($model->getQuestionStatusName()); ?></p>
    <p><strong>Автор вопроса:</strong> <?php echo CHtml::encode($model->authorName); ?></p>
    <p><strong>Город:</strong> <?php echo CHtml::encode($model->town->name); ?></p>
</div>

<?php echo CHtml::link('Редактировать вопрос', Yii::app()->createUrl('question/update',array('id'=>$model->id)), array('class'=>'btn btn-primary'));?>


<h2>Ответы</h2>
<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'  =>  $answersDataProvider,
	'itemView'      =>  'application.views.answer._view',
        'emptyText'     =>  'Не найдено ни одного ответа',
        'summaryText'   =>  'Показаны ответы с {start} до {end}, всего {count}',
        'pager'         =>  array('class'=>'GTLinkPager') //we use own pager with russian words

)); ?>

<?php echo CHtml::link('Добавить ответ', Yii::app()->createUrl('answer/create',array('questionId'=>$model->id)),array('class'=>'btn btn-primary')); ?>