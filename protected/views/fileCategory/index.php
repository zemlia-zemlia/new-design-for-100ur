<?php
/* @var $this FileCategoryController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs = [
    'File Categories',
];

$this->menu = [
    ['label' => 'Create FileCategory', 'url' => ['create']],
    ['label' => 'Manage FileCategory', 'url' => ['admin']],
];
?>

<h1>File Categories</h1>

<?php $this->widget('zii.widgets.CListView', [
    'dataProvider' => $dataProvider,
    'itemView' => '_view',
]); ?>
