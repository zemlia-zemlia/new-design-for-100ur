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



    /**
     * Загружает настройки из конфиг файлов с параметрами доступа к ящику и списком папок для парсинга
     * @param type $configMailBoxName
     * @param type $configFoldersName
     */
    public function __construct($configMailBoxName, $configFoldersName) {
        $this->loadAccessConfig($configMailBoxName);
        $this->loadFoldersSettings($configFoldersName);
        $this->setExistingPhones();
    }

    /**
     * Загрузка настроек папок и источников из конфиг файла
     * @param type $configFoldersName
     */
    public function loadFoldersSettings($configFoldersName) {
        $this->_folderSettings = require(Yii::getPathOfAlias('application.config.parsers.folders.' . $configFoldersName));
        if(!is_array($this->_folderSettings)) {
            throw new CException('Invalid folders config file');
        }
    }

    /**
     * Загрузка настроек подключения к почтовому ящику из конфиг файла
     * @param type $configFoldersName
     */
    public function loadAccessConfig($configMailBoxName) {
        $this->_accessConfig = require(Yii::getPathOfAlias('application.config.parsers.folders.' . $configMailBoxName));
        if(!is_array($this->_accessConfig)) {
            throw new CException('Invalid access config file');
        }
    }

    public function getMessagesFromFolder($folderName) {
        
    }

    public function echoDebug($message) {
        if ($this->_debugMode == true) {
            print_r($message . PHP_EOL);
        }
    }

    /**
     * Классы наследники должны реализовать метод парсинга текста письма
     */
    abstract public function parseMessage();

    /**
     * Поиск в базе телефонов существующих лидов
     * @param integer $period За сколько часов собирать лиды
     */
    public function setExistingPhones($period = 48) {
        
        $existingLeads = Lead100::model()->findAll(array(
            'condition' => 'question_date>NOW()- INTERVAL ' . $period . 'HOUR AND sourceId IN(' . implode(', ', $this->_leadSourceIds) . ')',
        ));

        // массив, в котором будут храниться телефоны лидов, которые добавлены в базу за последний день, чтобы не добавить одного лида несколько раз
        $existingLeadsPhones = array();

        foreach ($existingLeads as $existingLead) {
            $this->_existingPhones[] = Question::normalizePhone($existingLead->phone);
        }
    }
    
    /**
     * Записывает в свойство _leadSourceIds уникальные id источников лидов из конфига папок
     */
    public function setLeadSourcesIds()
    {
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
        
    }

}
