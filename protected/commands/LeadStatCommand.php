<?php
class LeadStatCommand extends CConsoleCommand
{
    public $daysInterval = 10; // за сколько дней выбирать статистику
    public $emails = array(
            'misha.sunsetboy@gmail.com',
            '89269643535@mail.ru',
        );
    
    // рассылка статистики продаж лидов за последние N дней
    public function actionIndex()
    {
        
            $mailer = new GTMail;
            
            $leadsRows = Yii::app()->db->createCommand()
                    ->select('SUM(l.price) summa, DATE(l.question_date) lead_date')
                    ->from('{{lead100}} l')
                    ->where('l.price != 0 AND leadStatus =' . Lead100::LEAD_STATUS_SENT.' AND l.question_date>NOW()-INTERVAL '.($this->daysInterval +1 ).' DAY')
                    ->group("lead_date")
                    ->order('lead_date DESC')
                    ->queryAll();
            //print_r($leadsRows);
            //exit;
            
            $mailer->subject = "Отчет по продажам лидов сервисом 100 юристов";
            
            // убираем из массива последний элемент, т.к. в нем статистика за неполные сутки $daysInterval суток назад
            array_pop($leadsRows);
            
            $mailer->message = '<h2>Статистика продаж лидов</h2>';
            $mailer->message .= "<table>";
            
            foreach($leadsRows as $dayStats) {
                $mailer->message .= "<tr><td>" . $dayStats['lead_date'] . "</td><td> : " . $dayStats['summa'] . " руб.</td></tr>";
            }
            
            $mailer->message .= "</table>";
                            
            
            foreach($this->emails as $email) {
                $mailer->email = $email;

                $mailer->sendMail(true);
            }
        
        
    }
}