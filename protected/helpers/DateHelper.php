<?php

/**
 * Class DateHelper
 * @todo Refactor me
 */
class DateHelper
{

    /**
     * @return array
     */
    public static function getWeekDays(): array
    {
        return array(
            1 => 'пн',
            2 => 'вт',
            3 => 'ср',
            4 => 'чт',
            5 => 'пт',
            6 => 'сб',
            7 => 'вс',
        );
    }

    /**
     * @return array
     */
    public static function getMonthsNames(): array
    {
        return array(
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
        );
    }

    /**
     * функция возвращает время в формате hh:mm из даты yyyy-mm-dd hh:mm:ss
     * @param string $date
     * @return string|null
     */
    public static function showTime(string $date): ?string
    {
        if (stristr($date, " ")) {
            //это дата+время
            $dateTimeArray = explode(" ", $date);
            $time = $dateTimeArray[1];
            $timeArray = explode(":", $time);
            $hours = $timeArray[0];
            $minutes = $timeArray[1];
            $seconds = $timeArray[2];
            return $hours . ":" . $minutes;
        } else {
            return null;
        }
    }

    /**
     * Заполняет массив, ключами которого являются даты, недостающими датами и значениями по умолчанию
     * @param array $datesArray
     * @param mixed $defaultValue
     * @param string $interval Интервал между соседними датами в формате P1D
     * @return array
     * @throws \Exception
     */
    public static function fillEmptyDatesArrayByDefaultValues($datesArray, $defaultValue = 0, $interval = 'P1D')
    {
        if (sizeof($datesArray) == 0) {
            return $datesArray;
        }

        $dateStart = min(array_keys($datesArray));
        $dateEnd = max(array_keys($datesArray));
        $dateTimeEnd = new DateTime($dateEnd);

        $currentDate = (new DateTime($dateStart))->add(new DateInterval('P1D'));

        while ($currentDate < $dateTimeEnd) {
            if (!isset($datesArray[$currentDate->format('Y-m-d')])) {
                $datesArray[$currentDate->format('Y-m-d')] = $defaultValue;
            }
            $currentDate->add(new DateInterval('P1D'));
        }

        return $datesArray;
    }

    /**
     * принимает дату yyyy-mm-dd hh:mm:ss и возвращает массив из года, месяца, дня, часа, минуты, секунды, даты и времени
     * @param string $dateTime
     * @return array|null
     */
    public static function dateTimeArray(string $dateTime): ?array
    {
        if (stristr($dateTime, " ")) {
            //это дата+время
            $dateTimeArray = explode(" ", $dateTime);
            $dateArray = explode("-", $dateTimeArray[0]);
            $time = $dateTimeArray[1];
            $year = (int)$dateArray[0];
            $month = (int)$dateArray[1];
            $day = (int)$dateArray[2];
            $timeArray = explode(":", $time);
            $hours = (int)$timeArray[0];
            $minutes = $timeArray[1];
            $seconds = $timeArray[2];
            $outputArray = array(
                'year' => $year,
                'month' => $month,
                'day' => $day,
                'hours' => $hours,
                'minutes' => $minutes,
                'seconds' => $seconds,
                'date' => $dateTimeArray[0],
                'time' => $dateTimeArray[1]
            );
            return $outputArray;
        } elseif (stristr($dateTime, "-")) {
            $dateArray = explode("-", $dateTime);
            $year = (int)$dateArray[0];
            $month = (int)$dateArray[1];
            $day = (int)$dateArray[2];
            $outputArray = array(
                'year' => $year,
                'month' => $month,
                'day' => $day,
            );
            return $outputArray;
        } else {
            return null;
        }
    }

    /**
     * @param string $date
     * @return bool|string|null
     */
    public static function invertDate(?string $date): ?string
    {
        if ($date == '' || $date == null) {
            return null;
        }
        if (preg_match("/([0-9]{4})\-([0-9]{2})\-([0-9]{2})/", $date)) {
            // аргумент $date - строка формата yyyy-mm-dd
            $dateArray = explode("-", $date);
            $year = $dateArray[0];
            $month = $dateArray[1];
            $day = $dateArray[2];

            // возвращаем дату в формате dd-mm-yyyy
            return $day . "-" . $month . "-" . $year;
        } elseif (preg_match("/([0-9]{2})\-([0-9]{2})\-([0-9]{4})/", $date)) {
            // аргумент $date - строка формата dd-mm-yyyy, разбиваем строку на части
            $dateArray = explode("-", $date);
            $year = $dateArray[2];
            $month = $dateArray[1];
            $day = $dateArray[0];
            // возвращаем дату в формате yyyy-mm-dd
            return $year . "-" . $month . "-" . $day;
        } else {
            return null;
        }
    }

    /**
     * функция преобразует дату из формата 2012-09-01 12:30:00 в Пн 1 сен. 2012 12:30
     * @param string $date
     * @param bool $showTime
     * @param bool $showWeekday
     * @param bool $showYear
     * @return string
     */
    public static function niceDate(
        string $date,
        bool $showTime = true,
        bool $showWeekday = true,
        bool $showYear = true
    ): string
    {
        $monthsArray = array('', 'янв.', 'фев.', 'мар.', 'апр.', 'мая', 'июн.', 'июл.', 'авг.', 'сен.', 'окт.', 'ноя.', 'дек.');
        $weekDaysArray = array('Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб');
        $timestamp = strtotime($date);
        $weekdayNumber = date('w', $timestamp);
        $weekday = $weekDaysArray[$weekdayNumber];
        $dateTimeArray = self::dateTimeArray($date);

        $dateString = $dateTimeArray['day'] . " " . $monthsArray[$dateTimeArray['month']];

        if ($showYear === true) {
            $dateString .= " " . $dateTimeArray['year'];
        }

        if ($showTime === true) {
            $dateString .= " " . $dateTimeArray['hours'] . ":" . $dateTimeArray['minutes'];
        }

        if ($showWeekday === true) {
            $dateString = $weekday . " " . $dateString;
        }
        return $dateString;
    }
}
