<?php

/**
 * Скрипт парсинга лидов из писем от LeadLaw
 */
class ParseMailLeadlawCommand extends CConsoleCommand {

    public function actionIndex($debugMode = false) {
        $parser = EmailParserFactory::getParser('admin_100yuristov', 'leadlaw');
        $parser->run($debugMode);
    }
}
