<?php
/* @var $this DocTypeController */
/* @var $model DocType */

$this->breadcrumbs = [
    'Типы документов' => ['index'],
    $model->name => ['view', 'id' => $model->id],
    'Редактирование',
];

?>
<?php

$this->widget('zii.widgets.CBreadcrumbs', [
    'homeLink' => CHtml::link('100 юристов', '/admin'),
    'separator' => ' / ',
    'links' => $this->breadcrumbs,
 ]);

?>

<h1>Редактирование типа документа</h1>

<?php $this->renderPartial('_form', ['model' => $model]); ?>