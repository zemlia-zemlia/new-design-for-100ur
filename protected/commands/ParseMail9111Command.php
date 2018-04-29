<?php

/**
 * Скрипт парсинга лидов из писем от 9111
 */
class ParseMail9111Command extends CConsoleCommand {

    /**
     * Запуск парсинга писем
     * Параметры указываются при запуске скрипта, например:
     * ./yiic ParseMail9111 index --debugMode=false --period=4
     * @param type $debugMode включен ли дебаг
     * @param type $period период в сутках, за который парсим лиды
     */
    public function actionIndex($debugMode = false, $period = 2) {
        $parser = EmailParserFactory::getParser('admin_100yuristov', '9111');
        $parser->run($debugMode, $period);
    }
}
