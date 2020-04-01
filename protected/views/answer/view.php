<?php
/* @var $this AnswerController */

use App\models\Answer;

/* @var $model Answer */

$this->breadcrumbs = [
    'Answers' => ['index'],
    $model->id,
];

$this->menu = [
    ['label' => 'List App\models\Answer', 'url' => ['index']],
    ['label' => 'Create App\models\Answer', 'url' => ['create']],
    ['label' => 'Update App\models\Answer', 'url' => ['update', 'id' => $model->id]],
    ['label' => 'Delete App\models\Answer', 'url' => '#', 'linkOptions' => ['submit' => ['delete', 'id' => $model->id], 'confirm' => 'Are you sure you want to delete this item?']],
    ['label' => 'Manage App\models\Answer', 'url' => ['admin']],
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
