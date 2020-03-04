<?php
/* @var $this CategoryController */
/* @var $model Postcategory */

$this->breadcrumbs = [
    'Блог' => ['index'],
    'Новая категория',
];
$this->setPageTitle('Создание категории публикаций' . ' | ' . Yii::app()->name);

?>

<?php
    $this->widget('zii.widgets.CBreadcrumbs', [
        'homeLink' => CHtml::link('Консультация юриста', '/'),
        'separator' => ' / ',
        'links' => $this->breadcrumbs,
     ]);
?>

<h1>Новая категория публикаций</h1>

<?php echo $this->renderPartial('_form', ['model' => $model]); ?>