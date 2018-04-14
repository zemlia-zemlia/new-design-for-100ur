<?php

/**
 * абстрактный класс парсинга лидов из почты
 */
abstract class EmailParser {

    protected $_existingPhones = [];
    protected $_folderSettings;
    protected $_debugMode = false;
    protected $_accessConfig;
    protected $_leadSourceIds = [];
    protected $_messages = []; // массив объектов ParsedEmail

    /**
     * Загружает настройки из конфиг файлов с параметрами доступа к ящику и списком папок для парсинга
     * @param type $configMailBoxName
     * @param type $configFoldersName
     */
    public function __construct($configMailBoxName, $configFoldersName) {
        $this->loadAccessConfig($configMailBoxName);
        $this->loadFoldersSettings($configFoldersName);
    }

    /**
     * Загрузка настроек папок и источников из конфиг файла
     * @param type $configFoldersName
     */
    protected function loadFoldersSettings($configFoldersName) {
        $this->_folderSettings = require(Yii::getPathOfAlias('application.config.parsers.folders.' . $configFoldersName) . '.php');
        if (!is_array($this->_folderSettings)) {
            throw new CException('Invalid folders config file');
        }
    }

    /**
     * Загрузка настроек подключения к почтовому ящику из конфиг файла
     * @param type $configFoldersName
     */
    protected function loadAccessConfig($configMailBoxName) {
        $this->_accessConfig = require(Yii::getPathOfAlias('application.config.parsers.servers.' . $configMailBoxName) . '.php');
        if (!is_array($this->_accessConfig)) {
            throw new CException('Invalid access config file');
        }
    }

    /**
     * Извлекаем массив текстов непрочитанных писем из указанной папки
     * @param type $folderName
     * @return array Массив объектов ParsedEmail
     */
    protected function getMessagesFromFolder($folderName) {
        $host = $this->_accessConfig['server'];
        $port = $this->_accessConfig['port'];
        $login = $this->_accessConfig['login'];
        $pass = $this->_accessConfig['password'];
        $param = $this->_accessConfig['param'];
        $folder = 'INBOX/' . $folderName;
        
        $parsedMessages = [];
        
        // подключаемся к папке в почтовом ящике
        if (!$mbox = imap_open("{" . "{$host}:{$port}{$param}" . "}$folder", $login, $pass)) {
            throw new CException("Couldn't open the inbox");
        };

        // извлекаем письма из папки в ящике
        $emails = imap_search($mbox, 'UNSEEN SINCE ' . date('d-M-Y', strtotime("-1 day")));
        if ($emails == false && imap_errors()) {
            throw new CException("Messages search wrong criteria");
        }
        
        // Сообщений не найдено
        if($emails === false) {
            return $parsedMessages;
        }
            
        rsort($emails);

        // извлекаем из писем тексты и заголовки
        foreach ($emails as $emailId) {
            
            $emailBody = imap_fetchbody($mbox, $emailId, $this->getFetchBodySection());
            $emailSubject = iconv_mime_decode(imap_header($mbox, $emailId)->subject);
            
            $parsedMessages[] = new ParsedEmail($emailBody, $emailSubject);
        }

        imap_close($mbox);

        return $parsedMessages;
    }

    /**
     * Вывод сообщения, при условии, что парсер запущен в режиме отладки
     * @param mixed $message Текст сообщения или значение
     */
    protected function echoDebug($message) {
        if ($this->_debugMode == true) {
            print_r($message . PHP_EOL);
        }
    }

    /**
     * Классы-наследники должны реализовать метод парсинга текста письма
     */
    abstract protected function parseMessage(ParsedEmail $message, Lead100 $lead, $folderSettings);
    
    /**
     * Возвращает, какую секцию письма считать телом (третий параметр функции imap_fetchbody)
     */
    abstract protected function getFetchBodySection();

    /**
     * Поиск в базе телефонов существующих лидов
     * @param integer $period За сколько часов собирать лиды
     */
    protected function setExistingPhones($period = 48) {

        $existingLeads = Lead100::model()->findAll(array(
            'condition' => 'question_date>NOW()- INTERVAL ' . $period . ' HOUR AND sourceId IN(' . implode(', ', $this->_leadSourceIds) . ')',
        ));

        // массив, в котором будут храниться телефоны лидов, которые добавлены в базу за последний день, чтобы не добавить одного лида несколько раз
        $existingLeadsPhones = array();

        foreach ($existingLeads as $existingLead) {
            $this->_existingPhones[] = Question::normalizePhone($existingLead->phone);
        }
        
        $this->echoDebug($this->_existingPhones);
    }

    /**
     * Записывает в свойство _leadSourceIds уникальные id источников лидов из конфига папок
     */
    protected function setLeadSourcesIds() {
        foreach ($this->_folderSettings as $folder) {
            $this->_leadSourceIds[] = $folder['sourceId'];
        }
        $this->_leadSourceIds = array_unique($this->_leadSourceIds);
    }

    /**
     * Метод, вызывающий цепочку методов парсинга, является фасадом для клиентского кода
     * @param boolean $debugMode Работает ли скрипт в режиме отладки
     */
    public function run($debugMode = false) {
        
        $this->setLeadSourcesIds();
        $this->setExistingPhones();
        $this->_debugMode = $debugMode;

        foreach ($this->_folderSettings as $folderName => $folderSettings) {
            $this->echoDebug($folderName);
            $this->_messages = $this->getMessagesFromFolder($folderName);

            foreach ($this->_messages as $message) {
                $this->parseMessage($message, new Lead100, $folderSettings);
            }
        }
    }
    
    protected function loadHtmlParser()
    {
        // подключаем библиотеку для парсинга текста письма
        try{
            require_once Yii::getPathOfAlias('application.commands.simplehtmldom_1_5/simple_html_dom');
        } catch (CException $ex) {
            throw new CException("Can't load HTML parsing library");
        }
    }
    
}
