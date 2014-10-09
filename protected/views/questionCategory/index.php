<?php
/* @var $this QuestionCategoryController */
/* @var $dataProvider CActiveDataProvider */

$this->setPageTitle("Категории вопросов. ". Yii::app()->name);


$this->breadcrumbs=array(
        'Вопросы и ответы'=>array('/question'),
	'Категории вопросов',
);

?>
<div class="vert-margin30">
<h1>Категории вопросов</h1>
</div>

<ul>
<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
        'emptyText'     =>  'Не найдено ни одной категории',
        'summaryText'   =>  'Показаны категории с {start} до {end}, всего {count}',
        'pager'         =>  array('class'=>'GTLinkPager') //we use own pager with russian words
)); ?>
</ul>