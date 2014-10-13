<?php
/* @var $this QuestionController */
/* @var $model Question */

$this->setPageTitle(CHtml::encode($model->title) . ". Консультация юриста и адвоката. ". Yii::app()->name);

Yii::app()->clientScript->registerMetaTag(CHtml::encode(mb_substr($model->questionText, 0, 250,'utf-8')), 'description');

$this->breadcrumbs=array(
	'Вопросы'=>array('index'),
	$model->id,
);

?>

<div class="question-form-wrapper">
<h3>Задайте вопрос юристу бесплатно</h3>
<?php
    $this->renderPartial('application.views.question._formSimple', array(
            'model'=>$questionModel,
        ));
?>
</div>

<div class="vert-margin30">
<?php if($model->title):?>
<h1><?php echo CHtml::encode($model->title); ?></h1>
<?php endif;?>
</div>



<div class="vert-margin30">
<p>
    <?php echo nl2br(CHtml::encode($model->questionText));?>
</p>
</div>

<div class="vert-margin30">
    <p>
        <?php if($model->authorName):?>
            <span class="glyphicon glyphicon-user"></span>&nbsp;<?php echo CHtml::encode($model->authorName); ?> &nbsp;&nbsp;
        <?php endif;?>
        <?php if($model->town):?>
            <span class="glyphicon glyphicon-map-marker"></span>&nbsp;<?php echo CHtml::encode($model->town->name); ?> &nbsp;&nbsp;
        <?php endif;?>
        <?php if($model->category):?>
            <span class="glyphicon glyphicon-folder-open"></span>&nbsp;&nbsp;<?php echo CHtml::link(CHtml::encode($model->category->name),Yii::app()->createUrl('questionCategory/view',array('id'=>$model->category->id)));?> &nbsp;&nbsp;
        <?php endif;?>
    </p>
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
    <?php echo CHtml::link('<span class="glyphicon glyphicon-plus-sign"></span> Задайте свой вопрос юристу', Yii::app()->createUrl('question/create'), array('class'=>'btn btn-primary btn-lg','rel'=>'nofollow','onclick'=>'yaCounter26550786.reachGoal("submit_after_button"); return true;')); ?>
    <div>Это бесплатно. Вы получите ответ в течение 15 минут</div>
</div>
