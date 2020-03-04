<?php
    $this->setPageTitle('Добавление расхода. ' . Yii::app()->name);

    $this->breadcrumbs = [
    'Расходы' => ['index'],
    'Добавление',
];

$this->widget('zii.widgets.CBreadcrumbs', [
    'homeLink' => CHtml::link('100 Юристов', '/admin'),
    'separator' => ' / ',
    'links' => $this->breadcrumbs,
 ]);
?>

<h1 class="vert-margin30">Добавление расхода</h1>

<?php echo $this->renderPartial('_form', ['model' => $model]); ?>
