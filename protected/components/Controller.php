<?php

/**
 * Кастомизированный класс контроллера. 
 * Все контроллеры в приложении должны наследоваться от него
 */
class Controller extends CController {

    /**
     * Шаблон по умолчанию
     */
    public $layout = '//frontend/main';

    /**
     * @var array context menu items. This property will be assigned to {@link CMenu::items}.
     */
    public $menu = array();

    /**
     * @var array the breadcrumbs of the current page. The value of this property will
     * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
     * for more details on how to specify this property.
     */
    public $breadcrumbs = array();

    /**
     * Функция вызывается перед вызовом любого контроллера
     * @return boolean
     */
    public function init() {
        Yii::setPathOfAlias('YurcrmClient', Yii::getPathOfAlias('application.vendor.yurcrm.yurcrm-client.src'));

        // если пользователь неактивен, разлогиниваем его
        if (!Yii::app()->user->isGuest && Yii::app()->user->active100 == 0) {
            Yii::app()->user->logout();
            CController::redirect(Yii::app()->homeUrl);
        }

        // редирект на https версию, если зашли по http и это не локальный хост
        if (!Yii::app()->getRequest()->isSecureConnection && $_SERVER['REMOTE_ADDR'] != '127.0.0.1') {
            # Redirect to the secure version of the page.
            $url = 'https://' .
                    Yii::app()->getRequest()->serverName .
                    Yii::app()->getRequest()->requestUri;
            Yii::app()->request->redirect($url, true, 301);
            return false;
        }

        // если передан GET параметр autologin, попытаемся залогинить пользователя
        User::autologin($_GET);
        
        // если при заходе на сайт в ссылке присутствует параметр partnerAppId, запишем его в сессию
        if (isset($_GET['partnerAppId']) && !Yii::app()->user->getState('sourceId')) {
            $source = Yii::app()->db->createCommand()
                    ->select('id')
                    ->from('{{leadsource}}')
                    ->where('appId = :appId', array(':appId' => (int) $_GET['partnerAppId']))
                    ->queryRow();

            if ($source) {
                Yii::app()->user->setState('sourceId', $source['id']);
            }
        }
        
        /* Реферальная программа
         * если при заходе на сайт в ссылке присутствует параметр ref, запишем его в сессию
         */
        if (isset($_GET['ref']) && !Yii::app()->user->getState('ref')) {
            $refUser = Yii::app()->db->createCommand()
                    ->select('id')
                    ->from('{{user}}')
                    ->where('id = :refId', array(':refId' => (int) $_GET['ref']))
                    ->queryRow();

            if ($refUser) {
                Yii::app()->user->setState('ref', $refUser['id']);
            }
            
        }
        
        // проверка, сохранен ли в сессии пользователя ID его города
        $currentTownId = Yii::app()->user->getState('currentTownId');
        if (empty($currentTownId)) {
            // если не сохранен, определим по IP
//                echo "Город не сохранен в сессии";
            $currentTownByIp = CustomFuncs::detectTown();
//                echo "Город, определенный по IP: " .$currentTownByIp->name;
            if ($currentTownByIp) {
                Yii::app()->user->setState('currentTownId', $currentTownByIp->id);
                Yii::app()->user->setState('currentTownName', $currentTownByIp->name);
                Yii::app()->user->setState('currentTownRegionName', $currentTownByIp->region->name);
            } else {
                Yii::app()->user->setState('currentTownId', 0);
            }
        }

        if (Yii::app()->user->role == User::ROLE_JURIST) {
            Yii::app()->db->createCommand()
                    ->update('{{user}}', array('lastActivity' => date('Y-m-d H:i:s')), 'id=:id', array(':id' => Yii::app()->user->id));
        }
        
        $this->lastModified();
    }

    
    /**
     * Добавляет заголовок Last-modified
     */
    protected function lastModified() {
        $LastModified_unix = time() - 86400; // время последнего изменения страницы
        $LastModified = gmdate("D, d M Y H:i:s \G\M\T", $LastModified_unix);
        $IfModifiedSince = false;
        if (isset($_ENV['HTTP_IF_MODIFIED_SINCE']))
            $IfModifiedSince = strtotime(substr($_ENV['HTTP_IF_MODIFIED_SINCE'], 5));
        if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']))
            $IfModifiedSince = strtotime(substr($_SERVER['HTTP_IF_MODIFIED_SINCE'], 5));
        if ($IfModifiedSince && $IfModifiedSince >= $LastModified_unix) {
            header($_SERVER['SERVER_PROTOCOL'] . ' 304 Not Modified');
            Yii::app()->end();
        }
        header('Last-Modified: ' . $LastModified);
    }

}
