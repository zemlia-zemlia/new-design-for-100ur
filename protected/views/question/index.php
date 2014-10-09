<?php
/* @var $this QuestionController */
/* @var $dataProvider CActiveDataProvider */

$this->setPageTitle("Вопросы юристам.". Yii::app()->name);


$this->breadcrumbs=array(
	'Вопросы и ответы',
);

?>
<div class="vert-margin30">
<h1>Вопросы юристам</h1>
</div>


<div class="vert-margin30 center-align">
    <?php echo CHtml::link('<span class="glyphicon glyphicon-plus-sign"></span> Задать вопрос', Yii::app()->createUrl('question/create'), array('class'=>'btn btn-primary btn-lg','rel'=>'nofollow','onclick'=>'yaCounter26550786.reachGoal("submit_after_button"); return true;')); ?>
    <div>Это бесплатно. Вы получите ответ в течение 15 минут</div>
</div>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'      =>  '_view',
        'emptyText'     =>  'Не найдено ни одного вопроса',
        'summaryText'   =>  '',
        'pager'         =>  array('class'=>'GTLinkPager') //we use own pager with russian words

)); ?>

