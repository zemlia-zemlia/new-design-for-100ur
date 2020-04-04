<?php
/* @var $this LeadController */

use App\models\Lead;

/* @var $model Lead */

$this->setPageTitle('Новый лид' . Yii::app()->name);

$this->breadcrumbs = [
    'Лиды' => ['index'],
    'Добавление',
];

$this->widget('zii.widgets.CBreadcrumbs', [
    'homeLink' => CHtml::link('100 Юристов', '/admin'),
    'separator' => ' / ',
    'links' => $this->breadcrumbs,
 ]);
?>

<h1>Новый лид</h1>

<?php if (!empty($apiResult)):?>
    <h2>Результат запроса к API:</h2>
    <textarea class="form-control" rows="10">
        <?php print_r($apiResult); ?>
    </textarea>
<?php endif; ?>

<?php echo $this->renderPartial('_form', [
        'model' => $model,
        'allDirections' => $allDirections,
    ]); ?>
