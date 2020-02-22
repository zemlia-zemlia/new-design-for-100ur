<?php
$this->setPageTitle('Поиск вопросов. Консультация юриста и адвоката. ' . Yii::app()->name);
?>

        <h1>Вопросы юристам</h1>
             
<?php if (isset($dataProvider)):?>

<?php $this->widget('zii.widgets.CListView', [
    'dataProvider' => $dataProvider,
    'itemView' => 'application.views.question._viewSearch',
        'viewData' => [
            'hideCategory' => false,
        ],
        'emptyText' => 'Не найдено ни одного вопроса',
        'ajaxUpdate' => false,
        'summaryText' => '',
        'pager' => ['class' => 'GTLinkPager'], //we use own pager with russian words
]); ?>

<?php endif; ?>
