<?php
/* @var $this CountryController */

use App\models\Country;

/* @var $model Country */

$this->breadcrumbs = [
    'Countries' => ['index'],
    'Manage',
];

$this->menu = [
    ['label' => 'List App\models\Country', 'url' => ['index']],
    ['label' => 'Create App\models\Country', 'url' => ['create']],
];

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#country-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Countries</h1>

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
    'id' => 'country-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => [
        'id',
        'name',
        'alias',
        [
            'class' => 'CButtonColumn',
        ],
    ],
]); ?>
