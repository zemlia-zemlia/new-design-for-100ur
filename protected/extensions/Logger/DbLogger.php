<?php

namespace App\extensions\Logger;

use CDbConnection;
use Exception;

/**
 * Класс для записи лога в базу.
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
     * Запись сообщения в лог.
     *
     * @param string $message
     * @param string $class
     * @param int $id
     * @throws Exception
     */
    public function log($message, $class, $id)
    {
        $message = mb_substr($message, 0, 255);

        $this->connectionId->createCommand()
            ->insert($this->tableName, [
                'message' => $message,
                'class' => $class,
                'subjectId' => $id,
            ]);
    }
}
