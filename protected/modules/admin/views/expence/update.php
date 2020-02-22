<?php
    $this->setPageTitle('Редактирование расхода. ' . Yii::app()->name);

    $this->breadcrumbs = [
    'Расходы' => ['index'],
    'Редактирование',
];

$this->widget('zii.widgets.CBreadcrumbs', [
    'homeLink' => CHtml::link('100 Юристов', '/admin'),
    'separator' => ' / ',
    'links' => $this->breadcrumbs,
 ]);
?>

<h1 class="vert-margin30">Редактирование расхода</h1>

<?php echo $this->renderPartial('_form', ['model' => $model]); ?>