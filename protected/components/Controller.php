<?php
/**
 * Кастомизированный класс контроллера. 
 * Все контроллеры в приложении должны наследоваться от него
 */
class Controller extends CController
{
	/**
	 * Шаблон по умолчанию
	 */
	public $layout = '//frontend/main';
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu=array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs=array();
        
        /**
         * Функция вызывается перед вызовом любого контроллера
         * @return boolean
         */
        public function init()
        {
            // редирект на https версию, если зашли по http и это не локальный хост
            if ( !Yii::app()->getRequest()->isSecureConnection && $_SERVER['REMOTE_ADDR'] != '127.0.0.1') {
                # Redirect to the secure version of the page.
                $url = 'https://' .
                    Yii::app()->getRequest()->serverName .
                    Yii::app()->getRequest()->requestUri;
                    Yii::app()->request->redirect($url, true, 301);
                return false;
            }
            
            // проверка, сохранен ли в сессии пользователя ID его города
            $currentTownId = Yii::app()->user->getState('currentTownId');
            if(empty($currentTownId)) {
                // если не сохранен, определим по IP
//                echo "Город не сохранен в сессии";
                $currentTownByIp = CustomFuncs::detectTown();
//                echo "Город, определенный по IP: " .$currentTownByIp->name;
                if($currentTownByIp) {
                    Yii::app()->user->setState('currentTownId', $currentTownByIp->id);
                    Yii::app()->user->setState('currentTownName', $currentTownByIp->name);
                    Yii::app()->user->setState('currentTownRegionName', $currentTownByIp->region->name);  
                } else {
                    Yii::app()->user->setState('currentTownId', 0);
                }
                
            }
            
            if(Yii::app()->user->role == User::ROLE_JURIST) {
                Yii::app()->db->createCommand()
                        ->update('{{user}}', array('lastActivity' => date('Y-m-d H:i:s')), 'id=:id', array(':id' =>Yii::app()->user->id));
            }
        }
}