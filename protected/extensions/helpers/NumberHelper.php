<?php

namespace sto_yuristov\helpers;

/**
 *  Хелпер для работы с числами
 */
class NumberHelper
{
    /**
     * Возвращает слово в форме, соответствующей номеру. Например, 1 яблоко, 2 яблока, 5 яблок
     * @param integer $num
     * @param string $form1 форма для 1
     * @param string $form2 форма для 2
     * @param string $form5 форма для 5
     * @return string
     */
    public static function numForms($num, $form1, $form2, $form5)
    {
        $num10 = $num % 10;
        if ($num >= 10 && $num < 20) {
            return $form5;
        } else if ($num10 == 1) {
            return $form1;
        } else if ($num10 > 1 && $num10 < 5) {
            return $form2;
        }
        return $form5;
    }

}
