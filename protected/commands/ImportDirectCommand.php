<?php
/**
 * Консольная команда для импорта данных по расходу на директ
 */
class ImportDirectCommand extends CConsoleCommand
{
    public function actionFile()
    {
        $directData = file(__DIR__ . '/direct_stat/data.csv');
        
        // обходим построчно CSV файл с данными. Нас интересуют только строки с датой (цифрами) в начале
        foreach($directData as $dataString) {
            if(!preg_match('/^[0-9]+/', $dataString)) {
                continue;
            }
            
            $dataRow = explode(';', $dataString);
            
            $date = date('Y-m-d', strtotime($dataRow[0]));
            
            // данные за сегодня не записываем, т.к. они за неполный день
            if($date == date('d.m.Y')) {
                continue;
            }
            $expence = $dataRow[1];
            
            echo $date . ' - ' . $expence . PHP_EOL;
            
            // дата в таблице - уникальный ключ. Ловим неудачные попытки перезаписать данные на определенную дату
            try {
                Yii::app()->db->createCommand()
                    ->insert('{{expence}}', array(
                        'date'      => $date, 
                        'expences'  => $expence,
                        'type'      => Expence::TYPE_DIRECT,
                        'comment'   => "Расходы на Яндекс Директ",
                        ));
            } catch (CDbException  $e) {
                // ничего не делаем
            }
        }
    }
}