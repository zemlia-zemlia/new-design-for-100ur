<?php
/* @var $this AnswerController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs = [
    'Answers',
];

$this->menu = [
    ['label' => 'Create Answer', 'url' => ['create']],
    ['label' => 'Manage Answer', 'url' => ['admin']],
];
?>

<h1>Answers</h1>

<?php $this->widget('zii.widgets.CListView', [
    'dataProvider' => $dataProvider,
    'itemView' => '_view',
]); ?>
