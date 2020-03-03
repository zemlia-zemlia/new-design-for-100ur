<?php
/* @var $this CommentController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs = [
    'Comments',
];

$this->menu = [
    ['label' => 'Create Comment', 'url' => ['create']],
    ['label' => 'Manage Comment', 'url' => ['admin']],
];
?>

<h1>Comments</h1>

<?php $this->widget('zii.widgets.CListView', [
    'dataProvider' => $dataProvider,
    'itemView' => '_view',
]); ?>
