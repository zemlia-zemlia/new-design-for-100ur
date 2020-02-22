<?php
/* @var $this FileController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs = [
    'Files',
];

$this->menu = [
    ['label' => 'Create File', 'url' => ['create']],
    ['label' => 'Manage File', 'url' => ['admin']],
];
?>

<h1>Files</h1>

<?php $this->widget('zii.widgets.CListView', [
    'dataProvider' => $dataProvider,
    'itemView' => '_view',
]); ?>
