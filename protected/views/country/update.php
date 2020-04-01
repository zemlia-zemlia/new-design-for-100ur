<?php
/* @var $this CountryController */

use App\models\Country;

/* @var $model Country */

$this->breadcrumbs = [
    'Countries' => ['index'],
    $model->name => ['view', 'id' => $model->id],
    'Update',
];

$this->menu = [
    ['label' => 'List App\models\Country', 'url' => ['index']],
    ['label' => 'Create App\models\Country', 'url' => ['create']],
    ['label' => 'View App\models\Country', 'url' => ['view', 'id' => $model->id]],
    ['label' => 'Manage App\models\Country', 'url' => ['admin']],
];
?>

<h1>Update Country <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', ['model' => $model]); ?>