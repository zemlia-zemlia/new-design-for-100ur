<?php
/* @var $this QuestionController */
/* @var $model Question */

$this->setPageTitle(CHtml::encode($model->title) . ". Вопросы юристам. ". Yii::app()->name);

Yii::app()->clientScript->registerMetaTag(CHtml::encode(substr($model->questionText, 0, 250)), 'description');

$this->breadcrumbs=array(
	'Вопросы'=>array('index'),
	$model->id,
);

?>
<div class="vert-margin30">
<?php if($model->title):?>
<h1><?php echo CHtml::encode($model->title); ?></h1>
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
    <p><strong>Автор вопроса:</strong> <?php echo CHtml::encode($model->authorName); ?></p>
    <p><strong>Город:</strong> <?php echo CHtml::encode($model->town->name); ?></p>
</div>

<div class="vert-margin30">
<h2>Ответы</h2>
<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'  =>  $answersDataProvider,
	'itemView'      =>  'application.views.answer._view',
        'emptyText'     =>  'Не найдено ни одного ответа',
        'summaryText'   =>  '',
        'pager'         =>  array('class'=>'GTLinkPager') //we use own pager with russian words

)); ?>
</div>

<div class="vert-margin30 center-align">
    <?php echo CHtml::link('<span class="glyphicon glyphicon-plus-sign"></span> Задайте свой вопрос юристу', Yii::app()->createUrl('question/create'), array('class'=>'btn btn-primary btn-lg')); ?>
    <div>Это бесплатно. Вы получите ответ в течение 15 минут</div>
</div>
