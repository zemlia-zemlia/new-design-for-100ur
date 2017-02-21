<?php

class ContactForm extends CFormModel
{
	public $email;
        public $name;
        public $message;
        public $verifyCode;
        
        /**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			// username and password are required
			array('email, name, message', 'required','message'=>'Поле {attribute} не может быть пустым'),
			array('email', 'email','message'=>'Ваш Email не похож на настоящий'),
                        array('verifyCode','captcha','allowEmpty'=>!extension_loaded('gd'), 'message'   =>  'Проверочный код неправильный'),

		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
            return array(
                'email'     =>  'E-mail',
                'name'      =>  'Имя',
                'message'   =>  'Сообщение',
                'verifyCode'=>'Введите код с картинки. Робот не сможет это сделать, а человек - сможет',

            );
	}

}
