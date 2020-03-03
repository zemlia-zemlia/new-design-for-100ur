<?php

namespace sto_yuristov\helpers;

class PhoneHelper
{
    /**
     * Преобразует телефонные номера в стандартный формат 70000000000.
     *
     * @param type $phone
     *
     * @return type
     */
    public static function normalizePhone($phone)
    {
        if ('' == $phone) {
            return '';
        }
        // удаляем из номера все кроме цифр
        $digitalNumber = preg_replace('/([^0-9])/i', '', $phone);
        // берем последние 10 символов
        $digitalNumber = mb_substr($digitalNumber, -10, 10, 'utf-8');

        if (mb_strlen($digitalNumber, 'utf-8') < 11) {
            $digitalNumber = '7' . $digitalNumber;
        } elseif ('7' != mb_substr($digitalNumber, 0, 1, 'utf-8')) {
            $digitalNumber = substr_replace($digitalNumber, '7', 0, 1);
        }

        return $digitalNumber;
    }
}
