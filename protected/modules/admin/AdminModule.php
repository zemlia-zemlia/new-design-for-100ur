<?php

class AdminModule extends CWebModule
{
	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application

		// import the module-level models and components
		$this->setImport(array(
			'admin.models.*',
			'admin.components.*',
		));
	}

	public function beforeControllerAction($controller, $action)
	{
		//echo "hello admin"; exit;
                if(parent::beforeControllerAction($controller, $action))
		{
			// this method is called before any module controller action is performed
			// you may place customized code here

                    if (!(isset(Yii::app()->user) && !Yii::app()->user->isGuest && in_array(Yii::app()->user->role, array(
                            User::ROLE_ROOT,
                            User::ROLE_SECRETARY,
                        ))))
                    {
                        throw new CHttpException(403,'У Вас недостаточно прав для доступа к этой странице');
                        //Yii::app()->user->setReturnUrl(Yii::app()->createUrl($controller->getRoute()));
                        //$controller->redirect('/site/login');
                    }
                    return true;
		}
		else {
			return false;
                }
	}

}
