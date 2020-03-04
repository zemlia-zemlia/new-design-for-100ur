<?php

namespace sto_yuristov\helpers;

/**
 * Хелпер для работы с датами.
 */
class DateHelper
{
    // функция пребразует дату в формате yyyy-mm-dd в формат dd-mm-yyyy и наоборот в зависимости от формата аргумента
    public static function invertDate($date)
    {
        if ('' == $date) {
            return null;
        }
        if (preg_match("/([0-9]{4})\-([0-9]{2})\-([0-9]{2})/", $date)) {
            // аргумент $date - строка формата yyyy-mm-dd
            $dateArray = explode('-', $date);
            $year = $dateArray[0];
            $month = $dateArray[1];
            $day = $dateArray[2];

            // возвращаем дату в формате dd-mm-yyyy
            return $day . '-' . $month . '-' . $year;
        } elseif (preg_match("/([0-9]{2})\-([0-9]{2})\-([0-9]{4})/", $date)) {
            // аргумент $date - строка формата dd-mm-yyyy, разбиваем строку на части
            $dateArray = explode('-', $date);
            $year = $dateArray[2];
            $month = $dateArray[1];
            $day = $dateArray[0];
            // возвращаем дату в формате yyyy-mm-dd
            return $year . '-' . $month . '-' . $day;
        } else {
            return false;
        }
    }

    public static function getMonthsNames()
    {
        return [
            1 => 'январь',
            2 => 'февраль',
            3 => 'март',
            4 => 'апрель',
            5 => 'май',
            6 => 'июнь',
            7 => 'июль',
            8 => 'август',
            9 => 'сентябрь',
            10 => 'октябрь',
            11 => 'ноябрь',
            12 => 'декабрь',
        ];
    }

    public static function getWeekDays()
    {
        return [
            1 => 'пн',
            2 => 'вт',
            3 => 'ср',
            4 => 'чт',
            5 => 'пт',
            6 => 'сб',
            7 => 'вс',
        ];
    }

    // функция преобразует дату из формата 2012-09-01 12:30:00 в Пн 1 сен. 2012 12:30
    public static function niceDate($date, $showTime = true, $showWeekday = true)
    {
        $monthsArray = ['', 'янв.', 'фев.', 'мар.', 'апр.', 'мая', 'июн.', 'июл.', 'авг.', 'сен.', 'окт.', 'ноя.', 'дек.'];
        $weekDaysArray = ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'];
        $timestamp = strtotime($date);
        $weekdayNumber = date('w', $timestamp);
        $weekday = $weekDaysArray[$weekdayNumber];
        $dateTimeArray = self::dateTimeArray($date);

        $dateString = $dateTimeArray['day'] . ' ' . $monthsArray[$dateTimeArray['month']] . ' ' . $dateTimeArray['year'];

        if (true === $showTime) {
            $dateString .= ' ' . $dateTimeArray['hours'] . ':' . $dateTimeArray['minutes'];
        }
        if (true === $showWeekday) {
            $dateString = $weekday . ' ' . $dateString;
        }

        return $dateString;
    }

    // функция возвращает время в формате hh:mm из даты yyyy-mm-dd hh:mm:ss
    public static function showTime($date)
    {
        if (stristr($date, ' ')) {
            //это дата+время
            $dateTimeArray = explode(' ', $date);
            $time = $dateTimeArray[1];
            $timeArray = explode(':', $time);
            $hours = $timeArray[0];
            $minutes = $timeArray[1];
            $seconds = $timeArray[2];

            return $hours . ':' . $minutes;
        } else {
            return null;
        }
    }

    // функция принимает дату yyyy-mm-dd hh:mm:ss и возвращает массив из года, месяца, дня, часа, минуты, секунды, даты и времени
    public static function dateTimeArray($dateTime)
    {
        if (stristr($dateTime, ' ')) {
            //это дата+время
            $dateTimeArray = explode(' ', $dateTime);
            $dateArray = explode('-', $dateTimeArray[0]);
            $time = $dateTimeArray[1];
            $year = (int) $dateArray[0];
            $month = (int) $dateArray[1];
            $day = (int) $dateArray[2];
            $timeArray = explode(':', $time);
            $hours = (int) $timeArray[0];
            $minutes = $timeArray[1];
            $seconds = $timeArray[2];
            $outputArray = [
                'year' => $year,
                'month' => $month,
                'day' => $day,
                'hours' => $hours,
                'minutes' => $minutes,
                'seconds' => $seconds,
                'date' => $dateTimeArray[0],
                'time' => $dateTimeArray[1],
            ];

            return $outputArray;
        } elseif (stristr($dateTime, '-')) {
            $dateArray = explode('-', $dateTime);
            $year = (int) $dateArray[0];
            $month = (int) $dateArray[1];
            $day = (int) $dateArray[2];
            $outputArray = [
                'year' => $year,
                'month' => $month,
                'day' => $day,
            ];

            return $outputArray;
        } else {
            return null;
        }
    }
}
