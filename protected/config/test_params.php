<?php

// this contains the application parameters that can be maintained via GUI
return [
    'rangs' => require(dirname(__FILE__) . '/rangs.php'),
    'adminEmail' => '100yuristov@mail.ru',
    'leadsEmail' => 'admin@100yuristov.com',
    'adminNotificationsEmail' => 'admin@100yuristov.com',
    '100yuristovSourceId' => 3,  // id источника лидов, соответствующего 100 юристам
    'zakonSourceId' => 1,  // id источника лидов, соответствующего КЦ Закон
    'crmDomain' => 'http://crm',
    'yandexShopPassword' => '',
    'yandexShopId' => 0, // идентификатор магазина
    'yandexScid' => 0, // номер витрины
    'yandexPaymentAction' => '', // куда отправлять форму оплаты через Яндекс
    'mailBoxYurcrmLogin' => '',
    'mailBoxYurcrmPassword' => '',
    'mailBoxYurcrmServer' => '',
    'mailBoxYurcrmPort' => 993,
    'mailBoxYurcrmParam' => '',
    'questionPrice' => 5,
    'leadHoldPeriodDays' => 2, // сколько суток лид может быть забракован (в холде)
    'categories' => [
        'кредит' => 47,
        'МФО' => 1040,
        'коллектор' => 48,
        'колектор' => 48,
        'ипотек' => 608,
        'наслед' => 60,
        'снт' => 323,
        'приватизац' => 79,
        'жкх' => 1074,
        'тсж' => 327,
        'дтп' => 307,
        'пдд' => 307,
        'осаго' => 307,
        'каско' => 307,
        'алимент' => 5,
        'адвокат' => 383,
        'развод' => 330,
        'дду' => 1506,
        'банкрот' => 468,
    ],
    'sendpulseBooks' => [],
    'sendPulseApiId' => '',
    'sendPulseApiSecret' => '',
    'bonuses' => [
        //настройки бонусов за привлечение пользователей
        10 => 250, // за юриста 300 рублей
        3 => 50, // за клиента 50 рублей
    ],
    'priceCoeff' => 1.6,
    'yandexMoneySecret' => '',
    'smtpServer' => '',
    'smtpPort' => '',
    'smtpLogin' => '',
    'smtpPassword' => '',
    'smtpSenderEmail' => '',
    'logTable' => '{{log}}',
    'yurcrmToken' => '', // токен, по которому создаются пользователи в yurcrm
    'yurcrmDefaultTariff' => 5, // тариф по умолчанию для создаваемых пользователей в CRM
    'yurcrmApiUrl' => '', // адрес API CRM
    'yurcrmDomain' => '', // адрес YurCRM
    'donatesEnabled' => false,
    'webmaster100yuristovId' => 4025,
];
