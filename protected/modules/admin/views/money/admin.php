<?php
/* @var $this MoneyController */

use App\models\Money;

/* @var $model Money */

$this->breadcrumbs = [
    'Moneys' => ['index'],
    'Manage',
];

$this->menu = [
    ['label' => 'List App\models\Money', 'url' => ['index']],
    ['label' => 'Create App\models\Money', 'url' => ['create']],
];

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#money-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Moneys</h1>

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
    'id' => 'money-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => [
        'id',
        'accountId',
        'datetime',
        'type',
        'value',
        'comment',
        /*
        'direction',
        */
        [
            'class' => 'CButtonColumn',
        ],
    ],
]); ?>
