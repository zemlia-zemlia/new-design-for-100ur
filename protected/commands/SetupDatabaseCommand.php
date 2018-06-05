<?php

/**
 * Установка базы из эталонного дампа
 */
class SetupDatabaseCommand extends CConsoleCommand
{
    
    public function actionIndex()
    {
        $currentDSN  = explode('=', Yii::app()->db->connectionString);
        $dbname = $currentDSN[2];
        echo $dbname . PHP_EOL;
        echo dirname(__FILE__) . '/../db/100yuristov_etalon.sql' . PHP_EOL;
        exit;
        try {
            exec('mysql -u ' . Yii::app()->db->username . ' -p ' . Yii::app()->db->password . ' ' . $dbname . ' < ' . dirname(__FILE__) . '/../db/100yuristov_etalon.sql');
        } catch (Exception $ex) {
            throw $ex;
        }
        
    }
}