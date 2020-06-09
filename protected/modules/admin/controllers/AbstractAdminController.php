<?php

namespace App\modules\admin\controllers;

/**
 * Базовый контроллер для админки.
 */
abstract class AbstractAdminController extends \Controller
{
    // шаблон по умолчанию
    public $layout = '//admin/main';
}
