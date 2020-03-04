<?php if (!$showMy): ?>
    <?php echo $this->renderPartial('_searchForm', ['model' => $searchModel]); ?>
    <hr />
<?php endif; ?>

<?php
$this->widget('zii.widgets.CListView', [
    'dataProvider' => $dataProvider,
    'itemView' => '_view',
    'viewData' => [
        'showMy' => $showMy,
    ],
    'emptyText' => 'Не найдено ни одной заявки',
    'summaryText' => '',
    'pager' => ['class' => 'GTLinkPager'], //we use own pager with russian words
]);
?>