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
     * Правила проверки полей.
     */
    public function rules()
    {
        return [
                ['fromAccount, toAccount, sum, comment, datetime', 'required', 'message' => 'Поле {attribute} не может быть пустым'],
                ['fromAccount, toAccount', 'numerical', 'integerOnly' => true, 'message' => 'Поле {attribute} должно быть целым числом'],
                ['sum', 'numerical', 'message' => 'Поле {attribute} должно быть числом'],
                ['comment, datetime', 'length', 'max' => 255, 'message' => 'Поле {attribute} должно быть не длинее 255 символов'],
                ['toAccount', 'compare', 'operator' => '!=', 'compareAttribute' => 'fromAccount', 'message' => 'Нельзя переводить деньги со счета на тот же счет'],
            ];
    }

    /**
     * Наименования полей формы.
     */
    public function attributeLabels()
    {
        return [
                'fromAccount' => 'Со счета',
                'toAccount' => 'На счет',
                'sum' => 'Сумма',
                'comment' => 'Комментарий',
                'datetime' => 'Дата',
            ];
    }
}
