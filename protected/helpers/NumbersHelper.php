<?php

namespace App\helpers;

/**
 * Хелпер для работы с числами.
 */
class NumbersHelper
{
    /**
     * Возвращает слово в форме, соответствующей числу
     * Например: 1 яблоко, 2 яблока, 5 яблок.
     * @var $show показывать ли число вместе со строкой
     */
    public static function numForms(int $num, string $form1, string $form2, string $form5, $show = false): string
    {
        $num10 = $num % 10;
        if ($num >= 10 && $num < 20) {
            return $show ?  ($num . ' ' . $form5) : $form5;
        } elseif (1 == $num10) {
            return $show ?  ($num . ' ' . $form1) : $form1;
        } elseif ($num10 > 1 && $num10 < 5) {
            return $show ?  ($num . ' ' . $form2) : $form2;
        }

        return $show ?  ($num . ' ' . $form5) : $form5;
    }
}
