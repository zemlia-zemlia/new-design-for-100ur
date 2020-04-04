<?php
/* @var $this UserStatusRequestController */

use App\models\UserStatusRequest;

/* @var $model UserStatusRequest */

$this->breadcrumbs = [
    'User Status Requests' => ['index'],
    'Manage',
];

$this->menu = [
    ['label' => 'List App\models\UserStatusRequest', 'url' => ['index']],
    ['label' => 'Create App\models\UserStatusRequest', 'url' => ['create']],
];

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#user-status-request-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage User Status Requests</h1>

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
    'id' => 'user-status-request-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => [
        'id',
        'yuristId',
        'status',
        'isVerified',
        'vuz',
        'facultet',
        /*
        'education',
        'vuzTownId',
        'educationYear',
        'advOrganisation',
        'advNumber',
        'position',
        */
        [
            'class' => 'CButtonColumn',
        ],
    ],
]); ?>
