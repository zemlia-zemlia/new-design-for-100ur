с<?php
// this contains the application parameters that can be maintained via GUI
return [
    'rangs' => require(dirname(__FILE__) . '/rangs.php'),
    'adminEmail' => '100yuristov@mail.ru',
    'leadsEmail' => 'admin@100yuristov.com',
    'adminNotificationsEmail' => 'admin@100yuristov.com',
    '100yuristovSourceId' => 3,  // id источника лидов, соответствующего 100 юристам
    'zakonSourceId' => 1,  // id источника лидов, соответствующего КЦ Закон
    'crmDomain' => 'http://crm',
    'yandexShopPassword' => 'n9WTvvY5J',
    'yandexShopId' => 73868, // идентификатор магазина
    'yandexScid' => 542085, // номер витрины
    'yandexPaymentAction' => 'https://demomoney.yandex.ru/eshop.xml', // куда отправлять форму оплаты через Яндекс
    'mailBoxYurcrmLogin' => 'admin@100yuristov.com',
    'mailBoxYurcrmPassword' => 'vpn168dsl168',
    'mailBoxYurcrmServer' => 'imap.mail.ru',
    'mailBoxYurcrmPort' => 993,
    'mailBoxYurcrmParam' => '/imap/ssl/novalidate-cert',
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
    'sendpulseBooks' => [
        // массив соответствия ID ролей пользователей и адресных книг Sendpulse
        3 => 1367186, // клиенты (пользователи)
        6 => 1347377, // покупатели лидов
        7 => 1347445, // вебмастера
        10 => 1347446, // юристы
    ],
    'sendPulseApiId' => '83b196b8167efa203431239059e6aef5',
    'sendPulseApiSecret' => '72df25fae69d448db7f99753dc3cda03',
    'bonuses' => [
        //настройки бонусов за привлечение пользователей
        10 => 25000, // за юриста 300 рублей
        3 => 5000, // за клиента 50 рублей
    ],
    'priceCoeff' => 1.6,
    'yandexMoneySecret' => 'KUWxQxXHNKqj+yiQ6YOdSE/h',
    'smtpServer' => 'smtp-pulse.com',
    'smtpPort' => '465',
    'smtpLogin' => 'admin@100yuristov.com',
    'smtpPassword' => 'R9JjqWtq3fn',
    'smtpSenderEmail' => 'admin@100yuristov.com',
    'logTable' => '{{log}}',
    'yurcrmToken' => 'd5e599d3c15e6ce0ce9d3c5a17ca624b', // токен, по которому создаются пользователи в yurcrm
    'yurcrmDefaultTariff' => 5, // тариф по умолчанию для создаваемых пользователей в CRM
    'yurcrmApiUrl' => 'http://crm2/api/', // адрес API CRM
    'yurcrmDomain' => 'http://crm2', // адрес YurCRM
    'donatesEnabled' => false,
    'webmaster100yuristovId' => 4025, // id вебмастера, под которым создаются лиды источника 100 Юристов
    'geo_service_url' => 'http://geo.100yuristov',
    'sellLeadAfterCreating' => false, // пытаться продать лид сразу после создания
    'sovinform' => [
        'key' => '',
    ],
    'gainnet' => [
        'key' => '',
    ],
];
