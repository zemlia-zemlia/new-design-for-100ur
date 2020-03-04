<?php
/* @var $this CountryController */
/* @var $model Country */

$this->breadcrumbs = [
    'Countries' => ['index'],
    $model->name => ['view', 'id' => $model->id],
    'Update',
];

$this->menu = [
    ['label' => 'List Country', 'url' => ['index']],
    ['label' => 'Create Country', 'url' => ['create']],
    ['label' => 'View Country', 'url' => ['view', 'id' => $model->id]],
    ['label' => 'Manage Country', 'url' => ['admin']],
];
?>

<h1>Update Country <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', ['model' => $model]); ?>