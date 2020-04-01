<?php
/* @var $this PostController */

use App\models\Post;

/* @var $model Post */

$this->setPageTitle('Новый пост' . ' | Публикации' . ' | ' . Yii::app()->name);

$this->breadcrumbs = [
    'Блог' => ['/admin/blog'],
    'Новый пост',
];
?>

<?php
    $this->widget('zii.widgets.CBreadcrumbs', [
        'homeLink' => CHtml::link('100 Юристов', '/admin/'),
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