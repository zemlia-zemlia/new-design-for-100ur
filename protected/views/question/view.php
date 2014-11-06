<?php
/* @var $this QuestionController */
/* @var $model Question */

$this->setPageTitle(CHtml::encode($model->title) . ". Консультация юриста и адвоката. ". Yii::app()->name);

Yii::app()->clientScript->registerMetaTag(CHtml::encode(mb_substr($model->questionText, 0, 250,'utf-8')), 'description');

$this->breadcrumbs=array(
	CHtml::encode($model->category->name)   =>  array('questionCategory/alias','name'=>CHtml::encode($model->category->alias)),
	CHtml::encode($model->title),
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


<?php if($model->category):?>
    <?php
    $this->widget('zii.widgets.CBreadcrumbs', array(
        'homeLink'=>CHtml::link('Консультация юриста',"/"),
        'separator'=>' &rarr; ',
        'links'=>$this->breadcrumbs,
     ));
     ?>
<?php endif;?>

 <hr/>
<div class="well well-small"> 
<div>
<?php if($model->title):?>
<h1><?php echo CHtml::encode($model->title); ?></h1>
<?php endif;?>
</div>

<p>
    <?php echo nl2br(CHtml::encode($model->questionText));?>
</p>

<hr/>
<div >
    <p>
        <?php if($model->authorName):?>
            <span class="glyphicon glyphicon-user"></span>&nbsp;<?php echo CHtml::encode($model->authorName); ?> &nbsp;&nbsp;
        <?php endif;?>
        <?php if($model->town):?>
            <span class="glyphicon glyphicon-map-marker"></span>&nbsp;<?php echo CHtml::link(CHtml::encode($model->town->name),Yii::app()->createUrl('town/alias',array('name'=>CHtml::encode($model->town->alias)))); ?> &nbsp;&nbsp;
        <?php endif;?>
        <?php if($model->category):?>
            <span class="glyphicon glyphicon-folder-open"></span>&nbsp;&nbsp;<?php echo CHtml::link(CHtml::encode($model->category->name),Yii::app()->createUrl('questionCategory/alias',array('name'=>CHtml::encode($model->category->alias))));?> &nbsp;&nbsp;
        <?php endif;?>
    </p>
</div>
</div>

<div >
<div class="well well-small">
<h2>Ответ юриста</h2>
<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'  =>  $answersDataProvider,
	'itemView'      =>  'application.views.answer._view',
        'emptyText'     =>  'Не найдено ни одного ответа',
        'summaryText'   =>  '',
        'pager'         =>  array('class'=>'GTLinkPager') //we use own pager with russian words

)); ?>
</div>
</div>

<div class="vert-margin30 center-align">
    <?php echo CHtml::link('<span class="glyphicon glyphicon-plus-sign"></span> Получить консультацию юриста', Yii::app()->createUrl('question/create'), array('class'=>'btn btn-primary btn-lg','rel'=>'nofollow','onclick'=>'yaCounter26550786.reachGoal("submit_after_button"); return true;')); ?>
</div>

<hr/>
<div class="vert-margin30 well">
<h3 class="vert-margin30">Юридический портал работает при поддержке:</h3>
    <div class="row">
        <div class="col-md-3 col-sm-2 left-align">
            <img src="/pics/pravitelstvo.png" alt="При поддержке правительства РФ" class="img-responsive" />
        </div>

        <div class="col-md-3 col-sm-4 center-align">
            Правительство Российской Федерации 
        </div>

        <div class="col-md-3 col-sm-2 left-align"> 
            <img src="/pics/minyust.png" alt="При поддержке Министерства Юстиции" class="img-responsive" /> 
        </div>

        <div class="col-md-3 col-sm-4 center-align"> 
            Министерство Юстиции РФ
        </div>
    </div>
</div>
