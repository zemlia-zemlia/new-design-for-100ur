<?php

class WebmasterModule extends CWebModule
{
    public function init()
    {
        // this method is called when the module is being created
        // you may place code here to customize the module or the application
        // import the module-level models and components
        $this->setImport(array(
            'webmaster.models.*',
            'webmaster.components.*',
        ));
    }

    public function beforeControllerAction($controller, $action)
    {
        if (parent::beforeControllerAction($controller, $action)) {
            // this method is called before any module controller action is performed
            // you may place customized code here

            if (!Yii::app()->user->checkAccess(User::ROLE_PARTNER)) {
                throw new CHttpException(403, 'У Вас недостаточно прав для доступа к этой странице');
            }

            return true;
        } else {
            return false;
        }
    }
}
