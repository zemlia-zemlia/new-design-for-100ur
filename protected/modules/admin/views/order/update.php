<?php
/* @var $this OrderController */

use App\models\Order;

/* @var $model Order */
$this->setPageTitle('Редактирование заказа документов #' . $model->id . '. ' . Yii::app()->name);

$this->breadcrumbs = [
    'Заказы документов' => ['index'],
    $model->id => ['view', 'id' => $model->id],
    'Редактирование',
];

$this->widget('zii.widgets.CBreadcrumbs', [
    'homeLink' => CHtml::link('100 Юристов', '/admin'),
    'separator' => ' / ',
    'links' => $this->breadcrumbs,
 ]);
?>

<h1>Редактирование заказа документов #<?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', ['model' => $model]); ?>