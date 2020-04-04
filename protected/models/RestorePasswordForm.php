<?php

namespace App\models;

use CFormModel;

/**
 * Модель для формы восстановления пароля.
 */
class RestorePasswordForm extends CFormModel
{
    public $email;
    public $verifyCode;

    public function rules()
    {
        return [
            // username and password are required
            ['email', 'required'],
            ['verifyCode', 'captcha', 'allowEmpty' => !extension_loaded('gd')],
            ['email', 'email'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'email' => 'Ваш E-mail, с которым Вы зарегистрировались на сайте',
            'verifyCode' => 'Введите код с картинки. Робот не сможет это сделать, а человек - сможет',
        ];
    }
}
