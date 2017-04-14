<?php
/* @var $this QuestionCategoryController */
/* @var $dataProvider CActiveDataProvider */

$this->setPageTitle("Категории вопросов. ". Yii::app()->name);
Yii::app()->clientScript->registerMetaTag("Выберите интересующую вас категорию вопроса или задайте свой через специальную форму", 'description');

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
	'dataProvider'  =>  $dataProvider,
	'itemView'      =>  '_view',
        'emptyText'     =>  'Не найдено ни одной категории',
        'summaryText'   =>  '',
        'pager'         =>  array('class'=>'GTLinkPager'), //we use own pager with russian words
        'viewData'      =>  array('itemsCount' => $dataProvider->totalItemCount),
)); ?>
</ul>