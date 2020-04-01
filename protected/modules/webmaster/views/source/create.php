<?php
/* @var $this LeadsourceController */

use App\models\Leadsource;

/* @var $model Leadsource */

$this->pageTitle = 'Источники лидов. ' . Yii::app()->name;

$this->breadcrumbs = [
    'Источники лидов' => ['index'],
    'Новый',
];
$this->widget('zii.widgets.CBreadcrumbs', [
    'homeLink' => CHtml::link('Кабинет вебмастера', '/webmaster/'),
    'separator' => ' / ',
    'links' => $this->breadcrumbs,
 ]);
?>

<h1>Новый источник лидов</h1>

<?php echo $this->renderPartial('_form', ['model' => $model]); ?>