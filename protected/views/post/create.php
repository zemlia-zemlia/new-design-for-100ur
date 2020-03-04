<?php
/* @var $this PostController */
/* @var $model Post */

$this->setPageTitle('Новый пост' . ' | Публикации' . ' | ' . Yii::app()->name);

$this->breadcrumbs = [
    'Блог' => ['/blog'],
    'Новый пост',
];
?>

<?php
    $this->widget('zii.widgets.CBreadcrumbs', [
        'homeLink' => CHtml::link('Консультация юриста', '/'),
        'separator' => ' / ',
        'links' => $this->breadcrumbs,
     ]);
?>

<h1>Новый пост</h1>

<?php echo $this->renderPartial('_form', [
        'model' => $model,
        'categoriesArray' => $categoriesArray,
    ]);
?>