<?php
/* @var $this QuestionController */
/* @var $dataProvider CActiveDataProvider */

$this->setPageTitle("Вопросы юристам.". Yii::app()->name);
Yii::app()->clientScript->registerLinkTag("alternate","application/rss+xml","http://".$_SERVER['SERVER_NAME'].Yii::app()->createUrl('question/rss'));


$this->breadcrumbs=array(
	'Вопросы и ответы',
);

?>
<div class="vert-margin30">
<h1>Вопросы юристам</h1>
</div>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'      =>  '_view',
        'emptyText'     =>  'Юристы пока не ответили',
        'summaryText'   =>  '',
        'pager'         =>  array('class'=>'GTLinkPager') //we use own pager with russian words

)); ?>

