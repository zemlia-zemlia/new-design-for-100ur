<?php

/**
 * Модель для работы с формой логина
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
		return array(
			// username and password are required
			array('email, password', 'required','message'=>'Поле {attribute} не может быть пустым'),
			// rememberMe needs to be a boolean
			array('rememberMe', 'boolean'),
                        array('uid', 'numerical','integerOnly'=>true),
                        array('password','safe','on'=>'loginVk'),
			// password needs to be authenticated
			array('password', 'authenticate'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'rememberMe'=>'Запомнить',
			'email'=>'E-mail',
			'password'=>'Пароль',
		);
	}

	/**
	 * Проверка правильности введенных логина и пароля
	 */
	public function authenticate($attribute,$params)
	{
            $this->_identity=new UserIdentity($this->email, $this->password);
            
            if(!$this->_identity->authenticate()) {
                $this->addError('password','Неправильный E-mail или пароль, либо E-mail неактивирован');
            }
               
	}

	/**
	 * Пытается залогинить пользователя по email и паролю
         * 
	 * @return boolean true - залогинен, false - ошибка
	 */
	public function login()
	{
            if($this->_identity===null) {
                $this->_identity=new UserIdentity($this->email,$this->password);
                $this->_identity->authenticate();
            }
            
            if($this->_identity->errorCode===UserIdentity::ERROR_NONE) {
                $duration=$this->rememberMe ? 3600*24*30 : 0; // 30 days
                Yii::app()->user->login($this->_identity,$duration);
                return true;
            } else {
                return false;
            }
	}
}
