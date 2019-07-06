<?php
/* @var $this QuestionCategoryController */
/* @var $dataProvider CActiveDataProvider */

$this->setPageTitle("Темы вопросов");
Yii::app()->clientScript->registerMetaTag("Выберите интересующую вас категорию вопроса или задайте свой через специальную форму", 'description');

$this->breadcrumbs = array(
    'Вопросы и ответы' => array('/question'),
    'Темы',
);

?>
<div class="vert-margin30">
    <h1>Темы вопросов</h1>
</div>
<div class="vert-margin40">
    <div class="row">
        <?php $this->widget('zii.widgets.CListView', array(
            'dataProvider' => $dataProvider,
            'itemView' => '_view',
            'emptyText' => 'Не найдено ни одной темы',
            'summaryText' => '',
            'pager' => array('class' => 'GTLinkPager'), //we use own pager with russian words
            'viewData' => array('itemsCount' => $dataProvider->totalItemCount),
        )); ?>
    </div>
</div>

<h2 class="vert-margin30">Последние публикации</h2>

<div>
    <?php
    $this->widget('application.widgets.RecentCategories.RecentCategories', [
        'number' => 26,
        'template' => 'default1',
        'columns' => 3,
        ]);
    ?>
</div>