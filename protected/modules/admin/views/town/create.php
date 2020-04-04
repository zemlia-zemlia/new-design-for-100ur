<?php
/* @var $this TownController */

use App\models\Town;

/* @var $model Town */

$this->breadcrumbs = [
    'Towns' => ['index'],
    'Create',
];

$this->menu = [
    ['label' => 'List App\models\Town', 'url' => ['index']],
    ['label' => 'Manage App\models\Town', 'url' => ['admin']],
];
?>

<h1>Create Town</h1>

<?php echo $this->renderPartial('_form', ['model' => $model]); ?>