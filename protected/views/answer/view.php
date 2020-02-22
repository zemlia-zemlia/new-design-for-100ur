<?php
/* @var $this AnswerController */
/* @var $model Answer */

$this->breadcrumbs = [
    'Answers' => ['index'],
    $model->id,
];

$this->menu = [
    ['label' => 'List Answer', 'url' => ['index']],
    ['label' => 'Create Answer', 'url' => ['create']],
    ['label' => 'Update Answer', 'url' => ['update', 'id' => $model->id]],
    ['label' => 'Delete Answer', 'url' => '#', 'linkOptions' => ['submit' => ['delete', 'id' => $model->id], 'confirm' => 'Are you sure you want to delete this item?']],
    ['label' => 'Manage Answer', 'url' => ['admin']],
];
?>

<h1>View Answer #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', [
    'data' => $model,
    'attributes' => [
        'id',
        'questionId',
        'answerText',
        'authorId',
    ],
]); ?>
