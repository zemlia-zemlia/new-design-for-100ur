<?php if (!$showMy): ?>
    <?php echo $this->renderPartial('_searchForm', ['model' => $searchModel]); ?>
    <hr />
<?php endif; ?>

<?php
$this->widget('zii.widgets.CListView', array(
    'dataProvider' => $dataProvider,
    'itemView' => '_view',
    'viewData' => [
        'showMy' => $showMy,
    ],
    'emptyText' => 'Не найдено ни одной заявки',
    'summaryText' => '',
    'pager' => array('class' => 'GTLinkPager') //we use own pager with russian words
));
?>