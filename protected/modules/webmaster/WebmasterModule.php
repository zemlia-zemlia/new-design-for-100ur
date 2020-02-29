<?php

class WebmasterModule extends CWebModule
{

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
