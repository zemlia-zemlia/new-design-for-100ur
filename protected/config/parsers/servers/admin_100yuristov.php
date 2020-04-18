<?php
/**
 * Настройки подключения к почтовому ящику.
 */
return [
    'login' => getenv('MAILBOX_YURCRM_LOGIN'),
    'password' => getenv('MAILBOX_YURCRM_PASSWORD'),
    'server' => getenv('MAILBOX_YURCRM_SERVER'),
    'port' => getenv('MAILBOX_YURCRM_PORT'),
    'param' => getenv('MAILBOX_YURCRM_PARAM'),
];
