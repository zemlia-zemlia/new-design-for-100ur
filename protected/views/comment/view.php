<?php
/* @var $this CommentController */

use App\models\Comment;

/* @var $model Comment */

$this->breadcrumbs = [
    'Comments' => ['index'],
    $model->id,
];

$this->menu = [
    ['label' => 'List Comment', 'url' => ['index']],
    ['label' => 'Create Comment', 'url' => ['create']],
    ['label' => 'Update Comment', 'url' => ['update', 'id' => $model->id]],
    ['label' => 'Delete Comment', 'url' => '#', 'linkOptions' => ['submit' => ['delete', 'id' => $model->id], 'confirm' => 'Are you sure you want to delete this item?']],
    ['label' => 'Manage Comment', 'url' => ['admin']],
];
?>

<h1>View Comment #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', [
    'data' => $model,
    'attributes' => [
        'id',
        'type',
        'authorId',
        'objectId',
        'text',
        'dateTime',
    ],
]); ?>
