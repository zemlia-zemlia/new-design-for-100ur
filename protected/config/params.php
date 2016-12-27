<?php
// this contains the application parameters that can be maintained via GUI
return array(
	'adminEmail'    =>  '100yuristov@mail.ru',
        'leadsEmail'    =>  'admin@100yuristov.com',
        '100yuristovSourceId'   =>  3,  // id источника лидов, соответствующего 100 юристам
        'zakonSourceId'   =>  1,  // id источника лидов, соответствующего КЦ Закон
        'crmDomain'     =>  'http://crm',
        'yandexShopPassword'    =>  'n9WTvvY5J',
        'yandexShopId'  => 73868, // идентификатор магазина
        'yandexScid'    =>  542085, // номер витрины
        'yandexPaymentAction'   =>  'https://demomoney.yandex.ru/eshop.xml', // куда отправлять форму оплаты через Яндекс
        'mailBoxYurcrmLogin'      =>  "all@yurcrm.ru",
        'mailBoxYurcrmPassword'   =>  "vpn168dsl168",
        'mailBoxYurcrmServer'     =>  "imap.mail.ru",
        'mailBoxYurcrmPort'       =>  993,
        'mailBoxYurcrmParam'      => '/imap/ssl/novalidate-cert',
);
?>