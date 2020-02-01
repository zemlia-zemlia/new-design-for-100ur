<?php
/**
 * Класс для логирования информации в БД
 */
abstract class Logger
{
    abstract public function log($message, $class, $id);
}
