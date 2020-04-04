<?php
/* @var $this CodecsController */

use App\models\Codecs;

/* @var $model Codecs */

$this->breadcrumbs = [
    'App\models\Codecs' => ['index'],
    $model->id => ['view', 'id' => $model->id],
    'Update',
];

$this->menu = [
    ['label' => 'List App\models\Codecs', 'url' => ['index']],
    ['label' => 'Create App\models\Codecs', 'url' => ['create']],
    ['label' => 'View App\models\Codecs', 'url' => ['view', 'id' => $model->id]],
    ['label' => 'Manage App\models\Codecs', 'url' => ['admin']],
];
?>

<h1>Update Codecs <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', ['model' => $model]); ?>