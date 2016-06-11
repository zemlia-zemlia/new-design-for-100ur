<?php
/* @var $this QuestionCategoryController */
/* @var $dataProvider CActiveDataProvider */

$this->setPageTitle("Категории вопросов. ". Yii::app()->name);


$this->breadcrumbs=array(
        'Вопросы и ответы'=>array('/admin/question'),
	'Категории вопросов',
);

$this->widget('zii.widgets.CBreadcrumbs', array(
    'homeLink'=>CHtml::link('CRM',"/"),
    'separator'=>' / ',
    'links'=>$this->breadcrumbs,
 ));

 
 
?>

            <style>
    .comments-item, .table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
        padding:1px 1px;
    }
</style>

<div class="vert-margin30">
<h1>Категории вопросов</h1>
</div>

<div class="right-align vert-margin30">
<?php echo CHtml::link('Добавить категорию', Yii::app()->createUrl('/admin/questionCategory/create'), array('class'=>'btn btn-primary')); ?>
</div>

<table class="table table-bordered table-hover" >
    <tr>
        <th>Название категории</th>
        <th>Текст описания (верх)</th>
        <th>Текст описания (низ)</th>
        <th>H1</th>
        <th>Title</th>
        <th>Descr.</th>
        <th>Keyw.</th>
    </tr>
<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
        'emptyText'     =>  'Не найдено ни одной категории',
        'summaryText'   =>  'Показаны категории с {start} до {end}, всего {count}',
        'pager'         =>  array('class'=>'GTLinkPager') //we use own pager with russian words
)); ?>
</table>