<?php
/* @var $this CodecsController */

use App\models\Codecs;

/* @var $model Codecs */

$this->breadcrumbs = [
    'App\models\Codecs' => ['index'],
    'Create',
];

$this->menu = [
    ['label' => 'List App\models\Codecs', 'url' => ['index']],
    ['label' => 'Manage App\models\Codecs', 'url' => ['admin']],
];
?>

<h1>Create Codecs</h1>

<?php $this->renderPartial('_form', ['model' => $model]); ?>