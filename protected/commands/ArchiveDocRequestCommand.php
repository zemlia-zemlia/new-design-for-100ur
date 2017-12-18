<?php

/**
 * отправляем в архив заказы документов, которые не подтверждены или 
 * не выбран юрист в течение нескольких дней
 */

class ArchiveDocRequestCommand extends CConsoleCommand
{
    protected $_hours = 120; // сколько часов храним запрос на документ до архивации
    
    public function actionIndex()
    {
        $archiveResult = Yii::app()->db->createCommand()
                ->update('{{order}}', [
                        'status' => Order::STATUS_ARCHIVE,
                    ],
                    'status IN (:status1, :status2, :status3) AND `createDate`<NOW() - INTERVAL :hours HOUR',
                    [
                        ':status1'  => Order::STATUS_NEW, 
                        ':status2'  => Order::STATUS_CONFIRMED,
                        ':status3'  => Order::STATUS_CLOSED,
                        ':hours'    =>  $this->_hours,
                    ]
                );
        if($archiveResult === false) {
            Yii::log("Ошибка при архивации заказов документов", 'error', 'system.web');
        }
    }
}