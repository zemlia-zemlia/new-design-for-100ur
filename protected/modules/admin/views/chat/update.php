<?php
/* @var $this ChatController */
/* @var $model Chat */

$this->breadcrumbs = [
    'Chats' => ['index'],
    $model->id => ['view', 'id' => $model->id],
    'Update',
];

$this->menu = [
    ['label' => 'List Chat', 'url' => ['index']],
    ['label' => 'Create Chat', 'url' => ['create']],
    ['label' => 'View Chat', 'url' => ['view', 'id' => $model->id]],
    ['label' => 'Manage Chat', 'url' => ['admin']],
];
?>

<h1>Update Chat <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', ['model' => $model]); ?>