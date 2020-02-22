<?php
/* @var $this CodecsController */
/* @var $model Codecs */

$this->breadcrumbs = [
    'Codecs' => ['index'],
    $model->id => ['view', 'id' => $model->id],
    'Update',
];

$this->menu = [
    ['label' => 'List Codecs', 'url' => ['index']],
    ['label' => 'Create Codecs', 'url' => ['create']],
    ['label' => 'View Codecs', 'url' => ['view', 'id' => $model->id]],
    ['label' => 'Manage Codecs', 'url' => ['admin']],
];
?>

<h1>Update Codecs <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', ['model' => $model]); ?>