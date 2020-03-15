<?php

namespace App\helpers;

class StringHelper
{
    public static function printr($value): string
    {
        echo '<pre>';
        print_r($value);
        echo '</pre>';
    }

    /**
     * @param string $name
     *
     * @return string
     */
    public static function translit(string $name): string
    {
        $name = mb_strtolower($name, 'utf-8');
        $name = trim($name);
        $name = str_replace('а', 'a', $name);
        $name = str_replace('б', 'b', $name);
        $name = str_replace('в', 'v', $name);
        $name = str_replace('г', 'g', $name);
        $name = str_replace('д', 'd', $name);
        $name = str_replace('е', 'e', $name);
        $name = str_replace('ё', 'e', $name);
        $name = str_replace('ж', 'zh', $name);
        $name = str_replace('з', 'z', $name);
        $name = str_replace('и', 'i', $name);
        $name = str_replace('й', 'j', $name);
        $name = str_replace('к', 'k', $name);
        $name = str_replace('л', 'l', $name);
        $name = str_replace('м', 'm', $name);
        $name = str_replace('н', 'n', $name);
        $name = str_replace('о', 'o', $name);
        $name = str_replace('п', 'p', $name);
        $name = str_replace('р', 'r', $name);
        $name = str_replace('с', 's', $name);
        $name = str_replace('т', 't', $name);
        $name = str_replace('у', 'u', $name);
        $name = str_replace('ф', 'f', $name);
        $name = str_replace('х', 'h', $name);
        $name = str_replace('ц', 'c', $name);
        $name = str_replace('ч', 'ch', $name);
        $name = str_replace('ш', 'sch', $name);
        $name = str_replace('щ', 'sh', $name);
        $name = str_replace('ъ', 'j', $name);
        $name = str_replace('ы', 'y', $name);
        $name = str_replace('ь', '', $name);
        $name = str_replace('э', 'e', $name);
        $name = str_replace('ю', 'yu', $name);
        $name = str_replace('я', 'ya', $name);
        $name = str_replace(' ', '-', $name);
        $name = str_replace('_', '-', $name);

        return $name;
    }

    /**
     * Возвращает исходную строку с первым символом в верхнем регистре.
     *
     * @param string $string
     * @param string $encoding
     *
     * @return string
     */
    public static function mb_ucfirst(string $string, string $encoding = 'utf-8'): string
    {
        $strlen = mb_strlen($string, $encoding);
        $firstChar = mb_substr($string, 0, 1, $encoding);
        $then = mb_substr($string, 1, $strlen - 1, $encoding);

        return mb_strtoupper($firstChar, $encoding) . $then;
    }

    /**
     * Обрезает строку до необходимой длины, сохраняя последнее слово целым
     *
     * @param string $string Исходная строка
     * @param int $len Максимальная длина итоговой строки
     * @param string $encode Кодировка строки
     *
     * @return false|string
     */
    public static function cutString(string $string, int $len, string $encode = 'utf-8'): string
    {
        // если строка и так короткая, не делаем ничего
        if (mb_strlen($string, $encode) <= $len) {
            return $string;
        }

        $cuttedString = mb_substr($string, 0, $len, $encode);

        // определим позицию последнего пробела, чтобы в конце строки не было разрезанного слова
        $lastSpacePosition = mb_strripos($cuttedString, ' ', 0, $encode);

        $finalString = mb_substr($string, 0, $lastSpacePosition, $encode);

        return $finalString;
    }

    /**
     * Удаляет из строки символы, не входящие в шаблон.
     *
     * @param string $string Строка
     * @param string $patternWhite Шаблон разрешенных символов
     *
     * @return string
     */
    public static function filterString(string $string, string $patternWhite = '0-9a-zA-Zа-яА-ЯёЁ\-., '): string
    {
        return preg_replace('/[^0-9a-zA-Zа-яА-ЯёЁ\-., ]/', '', $string);
    }
}
