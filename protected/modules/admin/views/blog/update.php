<?php
/* @var $this CategoryController */
/* @var $model Postcategory */

$this->setPageTitle('Редактирование категории ' . CHtml::encode($model->title) . ' | Публикации' . ' | ' . Yii::app()->name);

$this->breadcrumbs = [
    'Публикации' => ['/category'],
    CHtml::encode($model->title) => ['view', 'id' => $model->id],
    'Редактирование',
];
?>

<?php
    $this->widget('zii.widgets.CBreadcrumbs', [
        'homeLink' => CHtml::link('CRM', '/'),
        'separator' => ' / ',
        'links' => $this->breadcrumbs,
     ]);
?>


<h1>Редактирование категории</h1>

<?php echo $this->renderPartial('_form', ['model' => $model]); ?>