<?php
/* @var $this QuestionCategoryController */
/* @var $model QuestionCategory */

$this->setPageTitle(CHtml::encode($model->id) . ". Категории вопросов. ". Yii::app()->name);

$this->breadcrumbs=array(
        'Вопросы и ответы'=>array('/admin/question'),
	'Категории вопросов'=>array('index'),
	CHtml::encode($model->name),
);

$this->widget('zii.widgets.CBreadcrumbs', array(
    'homeLink'=>CHtml::link('100 юристов',"/admin/"),
    'separator'=>' / ',
    'links'=>$this->breadcrumbs,
 ));
?>

<h1><?php echo CHtml::encode($model->name);?></h1>

<div class="vert-margin30">
<p><strong>Родительская категория:</strong> 
<?php if($model->parent):?>
    <?php echo Chtml::link(CHtml::encode($model->parent->name), Yii::app()->createUrl('/admin/questionCategory/view', array('id'=>$model->parent->id)));?>
<?php else:?>
    нет
<?php endif; ?>
</p>

<p><strong>Вложенные категории:</strong> 
    <?php foreach($model->children as $child):?>
        <?php echo CHtml::link(CHtml::encode($child->name), Yii::app()->createUrl('/admin/questionCategory/view', array('id'=>$child->id)));?> &nbsp; 
    <?php endforeach;?>
</p>
</div>

<div>
    <?php echo CHtml::link('Смотреть на сайте', Yii::app()->createUrl('/questionCategory/alias', array('name'=>$model->alias)), array('class'=>'btn btn-default', 'target' => '_blank'));?>
    <?php echo CHtml::link('Редактировать категорию', Yii::app()->createUrl('/admin/questionCategory/update', array('id'=>$model->id)), array('class'=>'btn btn-primary'));?>
    <?php echo CHtml::link('Добавить вопрос', Yii::app()->createUrl('/admin/question/create', array('categoryId'=>$model->id)), array('class'=>'btn btn-primary'));?>

</div>

<h2>Вопросы</h2>
<table class="table table-bordered table-hover table-striped">
    <thead>
    <tr>
        <th>Вопрос</th>
        <th>Категория</th>
        <th>Автор</th>
    </tr>
    </thead>
<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'  =>  $questionsDataProvider,
	'itemView'      =>  'application.views.question._view',
        'viewData'      =>  array(
            'hideCategory'  =>  true,
        ),
        'emptyText'     =>  'Не найдено ни одного вопроса',
        'summaryText'   =>  'Показаны вопросы с {start} до {end}, всего {count}',
        'pager'         =>  array('class'=>'GTLinkPager') //we use own pager with russian words
)); ?>
</table>