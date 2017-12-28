<?php
$this->setPageTitle("Заказы документов" . '. ' . Yii::app()->name);

// какие статусы заказов в каком порядке выводим на канбан доске
$kanbanStatuses = [
    Order::STATUS_NEW,
    Order::STATUS_CONFIRMED,
    Order::STATUS_JURIST_SELECTED,
    Order::STATUS_JURIST_CONFIRMED,
    Order::STATUS_DONE,
    Order::STATUS_REWORK,
    Order::STATUS_CLOSED,
];
?>

<h1>Заказы документов</h1>

<table class="table table-bordered">
    <tr>
        <th>Черновик</th>
        <th>Подтвержден</th>
        <th>Выбран юрист</th>
        <th>В работе</th>
        <th>Выполнен</th>
        <th>На доработке</th>
        <th>Закрыт</th>
    </tr>
    <tr>

        <?php foreach ($kanbanStatuses as $status): ?>
            <td>
                <?php if ($ordersByStatus[$status]): ?>
                    <?php foreach ($ordersByStatus[$status] as $orderId): ?>
                        <span class="label label-warning">
                            <?php echo CHtml::link($orderId, Yii::app()->createUrl('admin/order/view', ['id' => $orderId])); ?> 
                        </span> &nbsp;
                    <?php endforeach; ?>
                <?php endif; ?>
            </td>
        <?php endforeach; ?>

    </tr>
</table>

<table class="table table-bordered">
    <tr>
        <th>номер</th>
        <th>город</th>
        <th>дата</th>
        <th>тип</th>
        <th>юрист</th>
        <th>комм.</th>
    </tr>
    <?php
    $this->widget('zii.widgets.CListView', array(
        'dataProvider' => $ordersDataProvider,
        'itemView' => '_view',
        'emptyText' => 'Не найдено ни одного заказа',
        'summaryText' => 'Показаны заказы с {start} до {end}, всего {count}',
        'pager' => array('class' => 'GTLinkPager'), //we use own pager with russian words
    ));
    ?>
</table>
