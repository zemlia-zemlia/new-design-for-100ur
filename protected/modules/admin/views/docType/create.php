<?php
/* @var $this DocTypeController */

use App\models\DocType;

/* @var $model DocType */
$this->pageTitle = 'Новый тип документа. ' . Yii::app()->name;
$this->breadcrumbs = [
    'Типы документов' => ['index'],
    'Создание',
];

?>

<h1>Новый тип документа</h1>

<?php $this->renderPartial('_form', ['model' => $model]); ?>