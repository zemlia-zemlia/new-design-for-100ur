<?php

/**
 * Фабрика для классов парсинга лидов из почты.
 */
class EmailParserFactory
{
    /**
     * @param string $configMailBoxName
     * @param string $configFoldersName
     *
     * @return EmailParser
     *
     * @throws Exception
     */
    public function getParser($configMailBoxName, $configFoldersName)
    {
        $parserClassName = 'EmailParser' . $configFoldersName;
        if (class_exists($parserClassName)) {
            return new $parserClassName($configMailBoxName, $configFoldersName);
        } else {
            throw new Exception('Parser class not exists');
        }
    }
}
