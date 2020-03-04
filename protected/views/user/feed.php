<?php
    $this->setPageTitle('Лента новостей юриста. ' . Yii::app()->name);
    Yii::app()->clientScript->registerScriptFile('/js/feed.js');
?>

<h1>Лента новостей</h1>

    
<?php $this->widget('zii.widgets.CListView', [
            'dataProvider' => $feedDataProvider,
            'itemView' => '_viewFeed',
            'summaryText' => '',
            'ajaxUpdate' => false,
            'emptyText' => 'Здесь будут выводиться уведомления о новых комментариях к вашим ответам, обновления в заказах документов и другие интересные вещи.',
            'pager' => ['class' => 'GTLinkPager'],
    ]);
?>

    