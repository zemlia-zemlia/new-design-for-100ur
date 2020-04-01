<?php

use App\models\LoginForm;
use App\models\User;

class LoginWidget extends CWidget
{
    public $template = 'default'; // представление виджета по умолчанию

    public function run()
    {
        $model = new LoginForm();

        if (User::ROLE_PARTNER == Yii::app()->user->role) {
            $currentUser = User::model()->findByPk(Yii::app()->user->id);
        }

        $this->render($this->template, [
            'model' => $model,
            'currentUser' => $currentUser,
        ]);
    }
}
