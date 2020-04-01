<?php

namespace App\models;

use CFormModel;
use UserIdentity;
use Yii;

/**
 * Модель для работы с формой логина.
 */
class LoginForm extends CFormModel
{
    public $email;
    public $password;
    public $rememberMe;
    public $uid;
    private $_identity;

    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules()
    {
        return [
            // username and password are required
            ['email, password', 'required', 'message' => 'Поле {attribute} не может быть пустым'],
            // rememberMe needs to be a boolean
            ['rememberMe', 'boolean'],
            ['uid', 'numerical', 'integerOnly' => true],
            ['password', 'safe', 'on' => 'loginVk'],
            // password needs to be authenticated
            ['password', 'authenticate'],
        ];
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels()
    {
        return [
            'rememberMe' => 'Запомнить',
            'email' => 'E-mail',
            'password' => 'Пароль',
        ];
    }

    /**
     * Проверка правильности введенных логина и пароля.
     */
    public function authenticate($attribute, $params)
    {
        $this->_identity = new UserIdentity($this->email, $this->password);

        if (!$this->_identity->authenticate()) {
            $this->addError('password', 'Неправильный E-mail или пароль, либо E-mail неактивирован');
        }
    }

    /**
     * Пытается залогинить пользователя по email и паролю.
     *
     * @return bool true - залогинен, false - ошибка
     */
    public function login()
    {
        if (null === $this->_identity) {
            $this->_identity = new UserIdentity($this->email, $this->password);
            $this->_identity->authenticate();
        }

        if (UserIdentity::ERROR_NONE === $this->_identity->errorCode) {
            $duration = $this->rememberMe ? 3600 * 24 * 30 : 0; // 30 days
            Yii::app()->user->login($this->_identity, $duration);

            return true;
        } else {
            return false;
        }
    }
}
