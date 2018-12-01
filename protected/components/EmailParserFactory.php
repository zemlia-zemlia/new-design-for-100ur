<?php
/**
 * Фабрика для классов парсинга лидов из почты
 */
class EmailParserFactory
{
    public static function getParser($configMailBoxName, $configFoldersName)
    {
        $parserClassName = 'EmailParser'.$configFoldersName;
        if(class_exists($parserClassName)) {
            return new $parserClassName($configMailBoxName, $configFoldersName);
        } else {
            throw new Exception('Parser class not exists');
        }
    }
}