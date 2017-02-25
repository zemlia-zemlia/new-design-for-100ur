<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
	/**
	 * @var string the default layout for the controller view. Defaults to 'column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout='column1';
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
                echo "Город не сохранен в сессии";
                $currentTownByIp = CustomFuncs::detectTown();
                echo "Город, определенный по IP: " .$currentTownByIp->name;
                if($currentTownByIp) {
                    Yii::app()->user->setState('currentTownId', $currentTownByIp->id);
                    Yii::app()->user->setState('currentTownName', $currentTownByIp->name);
                    Yii::app()->user->setState('currentTownRegionName', $currentTownByIp->region->name);  
                } else {
                    Yii::app()->user->setState('currentTownId', 0);
                }
                
            }
        }
}