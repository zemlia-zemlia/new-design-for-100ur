<?php

/**
 * Класс для работы с формой обратной связи.
 */
class ContactForm extends CFormModel
{
    public $email;
    public $name;
    public $message;
    public $verifyCode;

    /**
     * Правила проверки полей.
     */
    public function rules()
    {
        return [
            // username and password are required
            ['email, name, message', 'required', 'message' => 'Поле {attribute} не может быть пустым'],
            ['email', 'email', 'message' => 'Ваш Email не похож на настоящий'],
                        ['verifyCode', 'captcha', 'allowEmpty' => !extension_loaded('gd'), 'message' => 'Проверочный код неправильный'],
        ];
    }

    /**
     * Наименования полей формы.
     */
    public function attributeLabels()
    {
        return [
                'email' => 'E-mail',
                'name' => 'Имя',
                'message' => 'Сообщение',
                'verifyCode' => 'Введите код с картинки. Робот не сможет это сделать, а человек - сможет',
            ];
    }
}
