<?php

/**
 * Класс для работы с формой добавления внутренней транзакции между счетами.
 */
class MoneyMove extends CFormModel
{
    public $fromAccount;
    public $toAccount;
    public $sum;
    public $comment;
    public $datetime;


    /**
     * Правила проверки полей
     */
    public function rules()
    {
        return array(
                array('fromAccount, toAccount, sum, comment, datetime', 'required','message'=>'Поле {attribute} не может быть пустым'),
                array('fromAccount, toAccount', 'numerical', 'integerOnly'=>true, 'message'=>'Поле {attribute} должно быть целым числом'),
                array('sum', 'numerical', 'message'=>'Поле {attribute} должно быть числом'),
                array('comment, datetime', 'length', 'max'=>255, 'message'=>'Поле {attribute} должно быть не длинее 255 символов'),
                array('toAccount', 'compare', 'operator' => '!=', 'compareAttribute'=>'fromAccount', 'message'=>'Нельзя переводить деньги со счета на тот же счет'),
            );
    }

    /**
     * Наименования полей формы
     */
    public function attributeLabels()
    {
        return array(
                'fromAccount'   =>  'Со счета',
                'toAccount'     =>  'На счет',
                'sum'           =>  'Сумма',
                'comment'       =>  'Комментарий',
                'datetime'      =>  'Дата',
            );
    }
}
