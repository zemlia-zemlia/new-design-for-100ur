<?php

/**
 * Класс для записи лога в базу
 */
class DbLogger extends Logger
{

    /**
     * @var CDbConnection
     */
    protected $connectionId;

    /**
     * @var string Имя таблицы лога
     */
    protected $tableName;

    public function __construct($connectionId, $tableName)
    {
        $this->connectionId = $connectionId;
        $this->tableName = $tableName;
    }

    /**
     * Запись сообщения в лог
     * @param type $message
     * @param type $class
     * @param type $id
     */
    public function log($message, $class, $id)
    {
        try {
            $this->connectionId->createCommand()
                    ->insert($this->tableName, [
                        'message' => $message,
                        'class' => $class,
                        'subjectId' => $id,
            ]);
        } catch (Exception $ex) {
            throw $ex;
        }
    }
}
