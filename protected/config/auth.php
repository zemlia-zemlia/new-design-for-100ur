<?php

// определение ролей доступа
use App\models\User;

return [
    User::ROLE_ROOT => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'Администратор системы',
        'children' => [
            User::ROLE_MANAGER,
            User::ROLE_EDITOR,
            User::ROLE_CALL_MANAGER],
        'bizRule' => null,
        'data' => null
    ],
    User::ROLE_MANAGER => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'Менеджер',
        'children' => [
            User::ROLE_JURIST,
            User::ROLE_OPERATOR,
            User::ROLE_SECRETARY,
            User::ROLE_PARTNER],
        'bizRule' => null,
        'data' => null
    ],
    User::ROLE_JURIST => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'сотрудник',
        'children' => [],
        'bizRule' => null,
        'data' => null
    ],
    User::ROLE_OPERATOR => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'оператор',
        'children' => [],
        'bizRule' => null,
        'data' => null
    ],
    User::ROLE_CALL_MANAGER => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'руководитель колл центра',
        'children' => [
            User::ROLE_OPERATOR],
        'bizRule' => null,
        'data' => null
    ],
    User::ROLE_SECRETARY => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'секретарь',
        'children' => [],
        'bizRule' => null,
        'data' => null
    ],
    User::ROLE_EDITOR => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'контент-менеджер',
        'children' => [],
        'bizRule' => null,
        'data' => null
    ],
    User::ROLE_CLIENT => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'клиент',
        'children' => [],
        'bizRule' => null,
        'data' => null
    ],
    User::ROLE_BUYER => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'покупатель лидов',
        'children' => [],
        'bizRule' => null,
        'data' => null
    ],
    User::ROLE_PARTNER => [
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'поставщик лидов',
        'children' => [],
        'bizRule' => null,
        'data' => null
    ]];
