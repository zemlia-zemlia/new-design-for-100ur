<?php

/**
 * Хелпер для работы с числами.
 */
class NumbersHelper
{
    /**
     * Возвращает слово в форме, соответствующей числу
     * Например: 1 яблоко, 2 яблока, 5 яблок.
     *
     * @param int    $num
     * @param string $form1
     * @param string $form2
     * @param string $form5
     *
     * @return string
     */
    public static function numForms(int $num, string $form1, string $form2, string $form5): string
    {
        $num10 = $num % 10;
        if ($num >= 10 && $num < 20) {
            return $form5;
        } elseif (1 == $num10) {
            return $form1;
        } elseif ($num10 > 1 && $num10 < 5) {
            return $form2;
        }

        return $form5;
    }
}
