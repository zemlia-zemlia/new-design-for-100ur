<?php

/**
 * отправляем в архив заказы документов, которые не подтверждены или
 * не выбран юрист в течение нескольких дней.
 */
class ArchiveDocRequestCommand extends CConsoleCommand
{
    protected $_hours = 120; // сколько часов храним запрос на документ до архивации

    public function actionIndex()
    {
        $criteria = new CDbCriteria();
        $criteria->addInCondition('status', [Order::STATUS_NEW, Order::STATUS_CONFIRMED, Order::STATUS_CLOSED]);
        $criteria->addCondition('`createDate`<NOW() - INTERVAL ' . $this->_hours . ' HOUR');

        $orders = Order::model()->findAll($criteria);

        foreach ($orders as $order) {
            $order->sendToArchive();
        }
    }
}
