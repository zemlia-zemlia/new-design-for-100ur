<?php
/* @var $this QuestionCategoryController */
/* @var $dataProvider CActiveDataProvider */

$this->setPageTitle('Темы вопросов');
Yii::app()->clientScript->registerMetaTag('Выберите интересующую вас категорию вопроса или задайте свой через специальную форму', 'description');

$this->breadcrumbs = [
    'Вопросы и ответы' => ['/question'],
    'Темы',
];

?>
<main class="main">


    <?php
    // выводим виджет с деревом категорий
    $this->widget('application.widgets.CategoriesTree.CategoriesTree', [
        'template' => 'columns',
    ]);
    ?>

    <?php
    $this->widget('application.widgets.RecentCategories.RecentCategories', [
        'number' => 12,
        'template' => 'default',
        'columns' => 3,
    ]);
    ?>
</main>