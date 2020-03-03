<?php
/* @var $this CommentController */
/* @var $model Comment */

$this->breadcrumbs = [
    'Comments' => ['index'],
    $model->id => ['view', 'id' => $model->id],
    'Update',
];

$this->menu = [
    ['label' => 'List Comment', 'url' => ['index']],
    ['label' => 'Create Comment', 'url' => ['create']],
    ['label' => 'View Comment', 'url' => ['view', 'id' => $model->id]],
    ['label' => 'Manage Comment', 'url' => ['admin']],
];
?>

<h1>Update Comment <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', ['model' => $model]); ?>