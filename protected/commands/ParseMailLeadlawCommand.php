<?php

/**
 * Скрипт парсинга лидов из писем от LeadLaw.
 */
class ParseMailLeadlawCommand extends CConsoleCommand
{
    /**
     * Параметры указываются при запуске скрипта, например:
     * ./yiic ParseMailLeadlaw index --debugMode=false --period=4.
     *
     * @param bool $debugMode включен ли дебаг
     * @param int  $period    период в сутках, за который парсим лиды
     *
     * @throws Exception
     */
    public function actionIndex($debugMode = false, $period = 2)
    {
        $emailParserFactory = new EmailParserFactory();
        $parser = $emailParserFactory->getParser('admin_100yuristov', 'leadlaw');
        $parser->run($debugMode, $period);
    }
}
