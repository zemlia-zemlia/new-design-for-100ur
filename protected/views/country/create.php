<?php
/* @var $this CountryController */

use App\models\Country;

/* @var $model Country */

$this->breadcrumbs = [
    'Countries' => ['index'],
    'Create',
];

$this->menu = [
    ['label' => 'List App\models\Country', 'url' => ['index']],
    ['label' => 'Manage App\models\Country', 'url' => ['admin']],
];
?>

<h1>Create Country</h1>

<?php $this->renderPartial('_form', ['model' => $model]); ?>