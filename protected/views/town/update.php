<?php
/* @var $this TownController */

use App\models\Town;

/* @var $model Town */

$this->breadcrumbs = [
    'Towns' => ['index'],
    $model->name => ['view', 'id' => $model->id],
    'Update',
];

$this->menu = [
    ['label' => 'List App\models\Town', 'url' => ['index']],
    ['label' => 'Create App\models\Town', 'url' => ['create']],
    ['label' => 'View App\models\Town', 'url' => ['view', 'id' => $model->id]],
    ['label' => 'Manage App\models\Town', 'url' => ['admin']],
];
?>

<h1>Update Town <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', ['model' => $model]); ?>