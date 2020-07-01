<?php

return [
    'rangs' => require(dirname(__FILE__) . '/rangs.php'),
    'adminEmail' => getenv('ADMIN_EMAIL'),
    'MinAnswerQntForChat' => 0,
    'leadsEmail' => getenv('LEADS_EMAIL'),
    'adminNotificationsEmail' => getenv('ADMIN_NOTIFICATIONS_EMAIL'),
    '100yuristovSourceId' => 3,  // id источника лидов, соответствующего 100 юристам
    'zakonSourceId' => 1,  // id источника лидов, соответствующего КЦ Закон
    'crmDomain' => getenv('CRM_DOMAIN'),
    'yandexShopPassword' => getenv('YANDEX_SHOP_PASSWORD'),
    'yandexShopId' => getenv('YANDEX_SHOP_ID'), // идентификатор магазина
    'yandexScid' => getenv('YANDEX_SCID'), // номер витрины
    'yandexPaymentAction' => getenv('YANDEX_PAYMENT_ACTION'), // куда отправлять форму оплаты через Яндекс
    'mailBoxYurcrmLogin' => getenv('MAILBOX_YURCRM_LOGIN'),
    'mailBoxYurcrmPassword' => getenv('MAILBOX_YURCRM_PASSWORD'),
    'mailBoxYurcrmServer' => getenv('MAILBOX_YURCRM_SERVER'),
    'mailBoxYurcrmPort' => getenv('MAILBOX_YURCRM_PORT'),
    'mailBoxYurcrmParam' => getenv('MAILBOX_YURCRM_PARAM'),
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
    'sendPulseApiId' => getenv('SENDPULSE_API_ID'),
    'sendPulseApiSecret' => getenv('SENDPULSE_API_SECRET'),
    'bonuses' => [
        //настройки бонусов за привлечение пользователей
        10 => 25000, // за юриста 300 рублей
        3 => 5000, // за клиента 50 рублей
    ],
    'priceCoeff' => 1.6,
    'yandexMoneySecret' => getenv('YANDEX_MONEY_SECRET'),
    'yandexMoneyCheckSignature' => (bool)getenv('YANDEX_MONEY_CHECK_SIGNATURE'),
    'smtpServer' => getenv('SMTP_SERVER'),
    'smtpPort' => getenv('SMTP_PORT'),
    'smtpLogin' => getenv('SMTP_LOGIN'),
    'smtpPassword' => getenv('SMTP_PASSWORD'),
    'smtpSenderEmail' => getenv('SMTP_SENDER_EMAIL'),
    'logTable' => '{{log}}',
    'yurcrmToken' => getenv('YURCRM_TOKEN'), // токен, по которому создаются пользователи в yurcrm
    'yurcrmDefaultTariff' => getenv('YURCRM_DEFAULT_TARIFF'), // тариф по умолчанию для создаваемых пользователей в CRM
    'yurcrmApiUrl' => getenv('YURCRM_API_URL'), // адрес API CRM
    'yurcrmDomain' => getenv('YURCRM_DOMAIN'), // адрес YurCRM
    'donatesEnabled' => false,
    'webmaster100yuristovId' => 4025,
    'geo_service_url' => getenv('GEO_SERVICE_URL'),
    'sellLeadAfterCreating' => true, // пытаться продать лид сразу после создания
    'yuristBonus' => [
        'bonusForGoodAnswer' => 2000, // бонус юристу за хороший ответ в копейках
        'fastAnswerCoefficient' => 2, // коэффициент бонуса юристу за хороший ответ
        'fastAnswerInterval' => 2, // интервал в часах, когда хороший ответ считается быстрым
    ],
    'sovinform' => [
        'key' => getenv('SOVINFORM_KEY'),
    ],
    'gainnet' => [
        'key' => getenv('GAINNET_KEY'),
    ],
    'pravoved' => [
        'key' => getenv('PRAVOVED_KEY'),
    ],
    'api8088' => [
        'key' => getenv('API_8088_KEY'),
    ],
    'leadia' => [
        'key' => getenv('LEADIA_KEY'),
    ],
    'lexprofit' => [
        'key' => getenv('LEXPROFIT_KEY'),
    ],
    'chat' => [
        'enabled' => getenv('CHAT_ENABLED'),
    ],
    'detectTownByIP' => getenv('DETECT_TOWN_BY_IP'),
    'townByIpService' => getenv('TOWN_BY_IP_SERVICE'),
    'bots' => [
        'SemrushBot',
        'YandexBot',
        'AhrefsBot',
        'Dotbot',
        'bingbot',
        'Mail.RU_Bot',
        'PetalBot',
        'Googlebot',
        'DynatraceSynthetic',
        'mj12bot',
        'ZoominfoBot',
    ],
    'balance_topup_enabled' => getenv('BALANCE_TOPUP_ENABLED'),
];
