<?php
/* @var $this TownController */
/* @var $model Town */

$this->breadcrumbs=array(
	'Города'=>array('index'),
	'Администрирование',
);

$this->pageTitle = "Города, администрирование. " . Yii::app()->name;

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#town-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Все города</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'town-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'name',
		'ocrug',
		'alias',
		array(
                        'name'      =>  'size',
                        'header'    =>  'Нас.'
                    ),
                /*
		'description1',
		'description2',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
