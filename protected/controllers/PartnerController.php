<?php

/**
 * Личный кабинет партнера - поставщика лидов.
 */
class PartnerController extends Controller
{
    public $layout = '//frontend/cabinet';

    /*
     * TODO почепму тут грузится шаблон кабинет а функционал вебмастера?
     */

    public function filters()
    {
        return [
            'accessControl', // perform access control for CRUD operations
        ];
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     *
     * @return array access control rules
     */
    public function accessRules()
    {
        return [
            ['allow', // разрешаем доступ только авторизованным пользователям
                'actions' => ['index', 'leads', 'viewLead', 'campaign'],
                'users' => ['@'],
                'expression' => 'Yii::app()->user->checkAccess(User::ROLE_PARTNER)',
            ],
            ['deny', // запрещаем все, что не разрешено
                'users' => ['*'],
            ],
        ];
    }

    /**
     * Главная страница кабинета.
     */
    public function actionIndex()
    {
        echo $this->render('index');
    }

    /**
     * Список купленных лидов.
     */
    public function actionLeads()
    {
    }

    /**
     * Страница просмотра лида.
     *
     * @param int $id id лида
     */
    public function actionViewLead($id)
    {
        // Обязательно делаем проверку, что лид получен от текущего пользователя (проверка по источнику)
    }

    /**
     * Просмотр кампании (источника).
     *
     * @param int $id id источника
     */
    public function actionCampaign($id)
    {
    }
}
