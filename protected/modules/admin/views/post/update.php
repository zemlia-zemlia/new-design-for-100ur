<?php
/* @var $this PostController */

use App\models\Post;

/* @var $model Post */

$this->setPageTitle('Редактирование поста ' . CHtml::encode($model->title) . ' | Публикации' . ' | ' . Yii::app()->name);

$this->breadcrumbs = [
    'Блог' => ['/blog'],
    CHtml::encode($model->title) => ['view', 'id' => $model->id],
    'Редактирование',
];
?>

<?php
    $this->widget('zii.widgets.CBreadcrumbs', [
        'homeLink' => CHtml::link('100 Юристов', '/'),
        'separator' => ' / ',
        'links' => $this->breadcrumbs,
     ]);
?>

<h1>Редактирование поста</h1>

<?php echo $this->renderPartial('_form', [
        'model' => $model,
        'categoriesArray' => $categoriesArray,
    ]); ?>