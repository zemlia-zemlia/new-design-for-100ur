<?php
/**
 * Модель для формы восстановления пароля
 */
class RestorePasswordForm extends CFormModel
{
    public $email;
    public $verifyCode;

    public function rules()
    {
        return array(
            // username and password are required
            array('email', 'required'),
            array('verifyCode','captcha','allowEmpty'=>!extension_loaded('gd')),
            array('email','email'),
        );
    }
    
    public function attributeLabels()
    {
        return array(
            'email'=>'Ваш E-mail, с которым Вы зарегистрировались на сайте',
            'verifyCode'=>'Введите код с картинки. Робот не сможет это сделать, а человек - сможет',
        );
    }
}
?>
