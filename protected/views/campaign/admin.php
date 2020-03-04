<?php
/* @var $this CampaignController */
/* @var $model Campaign */

$this->breadcrumbs = [
    'Campaigns' => ['index'],
    'Manage',
];

$this->menu = [
    ['label' => 'List Campaign', 'url' => ['index']],
    ['label' => 'Create Campaign', 'url' => ['create']],
];

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#campaign-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Campaigns</h1>

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
    'id' => 'campaign-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => [
        'id',
        'regionId',
        'townId',
        'timeFrom',
        'timeTo',
        'price',
        /*
        'balance',
        'leadsDayLimit',
        'brakPercent',
        'buyerId',
        'active',
        */
        [
            'class' => 'CButtonColumn',
        ],
    ],
]); ?>
