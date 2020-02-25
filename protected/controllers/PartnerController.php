<?php

/**
 * Личный кабинет партнера - поставщика лидов
 */
class PartnerController extends Controller
{
    public $layout = '//frontend/cabinet';

    /*
     * TODO почепму тут грузится шаблон кабинет а функционал вебмастера?
     */

    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
            array('allow', // разрешаем доступ только авторизованным пользователям
                'actions' => array('index', 'leads', 'viewLead', 'campaign'),
                'users' => array('@'),
                'expression' => 'Yii::app()->user->checkAccess(User::ROLE_PARTNER)',
            ),
            array('deny', // запрещаем все, что не разрешено
                'users' => array('*'),
            ),
        );
    }
    
    /**
     * Главная страница кабинета
     */
    public function actionIndex()
    {
        echo $this->render('index');
    }
    /**
     * Список купленных лидов
     */
    public function actionLeads()
    {
    }
    /**
     * Страница просмотра лида
     * @param integer $id id лида
     */
    public function actionViewLead($id)
    {
        // Обязательно делаем проверку, что лид получен от текущего пользователя (проверка по источнику)
    }
    
    /**
     * Просмотр кампании (источника)
     * @param integer $id id источника
     */
    public function actionCampaign($id)
    {
    }
}
