<?php
/* @var $this LeadController */
/* @var $model Lead */

$this->breadcrumbs = [
    'Leads' => ['index'],
    'Manage',
];

$this->menu = [
    ['label' => 'List Lead', 'url' => ['index']],
    ['label' => 'Create Lead', 'url' => ['create']],
];

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#lead-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Leads</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search', '#', ['class' => 'search-button']); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search', [
    'model' => $model,
]); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', [
    'id' => 'lead-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => [
        'id',
        'name',
        'phone',
        'sourceId',
        'question',
        'question_date',
        /*
        'townId',
        'leadStatus',
        */
        [
            'class' => 'CButtonColumn',
        ],
    ],
]); ?>
