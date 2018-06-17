<?php

/**
 * Класс для работы с User identity, используется при аутентификации
 */
class UserIdentity extends CUserIdentity
{

    protected $_id;
    public $autologinString;

    // добавляем свои константы для кодов ошибок
    const ERROR_USER_INACTIVE = 5;
    const ERROR_AUTOLOGIN_WRONG = 6;

    /**
     * Аутентификация пользователя
     * @return boolean успешна ли аутентификация
     */
    public function authenticate()
    {
        $user = User::model()->find('LOWER(email)=?', array(strtolower($this->username)));
        if ($user === null) {
            // если не нашли пользователя
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        } else if ($user->active100 != 1) {
            $this->errorCode = self::ERROR_USER_INACTIVE;
        } else if (!$user->validatePassword($this->password)) {
            // если неправильный пароль
            $this->errorCode = self::ERROR_PASSWORD_INVALID;
        } else {
            // все ок
            $this->_id = $user->id;
            $this->username = $user->email;
            $this->errorCode = self::ERROR_NONE;
        }

        return $this->errorCode == self::ERROR_NONE;
    }

    /**
     * аутентификация пользователя по строке autologin
     * @return int Код результата 
     */
    public function autologin()
    {
        // если передана пустая строка autologin
        if (!$this->autologinString) {
            return $this->errorCode = self::ERROR_AUTOLOGIN_WRONG;
        }

        // если пользователь уже залогинен, не перелогиниваем
        if (!Yii::app()->user->isGuest) {
            return $this->errorCode = self::ERROR_AUTOLOGIN_WRONG;
        }

        // ищем в базе пользователя по полю autologin
        $user = User::model()->find('autologin=?', array($this->autologinString));
        if ($user === null) {
            // если не нашли пользователя
            return $this->errorCode = self::ERROR_AUTOLOGIN_WRONG;
        }
        // если пользователь неактивен
        if ($user->active100 != 1) {
            return $this->errorCode = self::ERROR_USER_INACTIVE;
        }

        $this->_id = $user->id;
        $this->username = $user->email;
        $this->errorCode = self::ERROR_NONE;

        // после логина удаляем у пользователя поле autologin, чтобы не дать залогиниться по этому коду еще раз
        User::model()->updateByPk($user->id, array('autologin' => ''));

        LoggerFactory::getLogger('db')->log('Автологин пользователя #' . $user->id . '(' . $user->getShortName() . ')', 'User', $user->id);

        return $this->errorCode == self::ERROR_NONE;
    }

    /**
     * Возвращает id текущего авторизованного пользователя
     * 
     * @return integer id текущего пользователя
     */
    public function getId()
    {
        return $this->_id;
    }

}
