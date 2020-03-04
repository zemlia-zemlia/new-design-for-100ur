<?php
$this->setPageTitle('Заказы документов' . '. ' . Yii::app()->name);
?>

<h1><?php echo (true == $showMyOrders) ? 'Мои заказы' : 'Заказы'; ?>  документов</h1>

<ul class="nav nav-tabs vert-margin40">
    <li role="presentation" class="<?php echo (true == $showMyOrders) ? '' : 'active'; ?>">
        <?php echo CHtml::link('Новые <strong class="red">(' . Order::calculateNewOrders() . ')</strong>', Yii::app()->createUrl('/order/index')); ?>
        
    </li>
    <li role="presentation" class="<?php echo (true == $showMyOrders) ? 'active' : ''; ?>">
        <?php echo CHtml::link('Мои заказы', Yii::app()->createUrl('/order/index', ['my' => 1])); ?>
    </li>
</ul>

<table class="table table-bordered">
    <?php
    $this->widget('zii.widgets.CListView', [
        'dataProvider' => $ordersDataProvider,
        'itemView' => 'application.views.order._view',
        'emptyText' => 'Не найдено ни одного заказа',
        'summaryText' => 'Показаны заказы с {start} до {end}, всего {count}',
        'pager' => ['class' => 'GTLinkPager'], //we use own pager with russian words
    ]);
    ?>
</table>
