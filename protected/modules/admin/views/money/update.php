<?php
/* @var $this MoneyController */
/* @var $model Money */

$this->breadcrumbs = [
    'Moneys' => ['index'],
    $model->id => ['view', 'id' => $model->id],
    'Update',
];

?>

<h1>Редактирование записи кассы <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', ['model' => $model]); ?>