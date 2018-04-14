<?php

/**
 * Скрипт парсинга лидов из писем от 9111
 */
class ParseMail9111Command extends CConsoleCommand {

    public function actionIndex($debugMode = false) {
        $parser = EmailParserFactory::getParser('admin_100yuristov', '9111');
        $parser->run($debugMode);
    }
}
