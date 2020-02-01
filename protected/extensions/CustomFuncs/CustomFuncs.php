<?php

/**
 * Сборник самописных методов, применяемых в проекте.
 * @todo Отрефакторить, вынести методы в отдельные классы
 */
class CustomFuncs
{
    public static function numForms($num, $form1, $form2, $form5)
    {
        $num10 = $num % 10;
        if ($num >= 10 && $num < 20) {
            return $form5;
        } elseif ($num10 == 1) {
            return $form1;
        } elseif ($num10 > 1 && $num10 < 5) {
            return $form2;
        } else {
            return $form5;
        }
    }

    /**
     * Определение города пользователя по IP адресу
     * @param string $ip
     * @return Town город или NULL
     */
    public static function detectTown($ip = null)
    {
        $town = null;

        if (!Yii::app()->user->getState('currentTownId')) {
            // если принудительно не задан IP, берем текущий IP адрес
            if (empty($ip)) {
                $ip = self::getUserIP();
            }

            $data = "<ipquery><fields><all/></fields><ip-list><ip>" . $ip . "</ip></ip-list></ipquery>";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "http://194.85.91.253:8090/geo/geo.html");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            $xml = curl_exec($ch);
            curl_close($ch);
            $xml = iconv('windows-1251', 'utf-8', $xml);
            preg_match("/<city>(.*?)<\/city>/", $xml, $a);
            $townName = isset($a[1]) ? $a[1] : '';
            //echo 'Город:' . $townName; Yii::app()->end();

            $currentTown = null;
            if ($townName) {
                $currentTown = Town::model()->findByAttributes(array('name' => $townName));
            }
            return $currentTown;
        }
        return $town;
    }

    // временная функция
    public static function detectTownTest($ip = null)
    {
        if (!Yii::app()->user->getState('currentTown')) {
            //if(empty($ip)) $ip=$_SERVER['REMOTE_ADDR'];
            if (empty($ip)) {
                $ip = $_SERVER['HTTP_X_REAL_IP'];
            } else {
                $ip = "79.111.82.114";
            }
            $data = "<ipquery><fields><all/></fields><ip-list><ip>" . $ip . "</ip></ip-list></ipquery>";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "http://194.85.91.253:8090/geo/geo.html");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

            $xml = curl_exec($ch);
            echo "<pre>";
            print_r(curl_getinfo($ch));
            echo "</pre>";
            if ($curlError = curl_error($ch)) {
                echo "CURL Error: " . $curlError;
            }
            curl_close($ch);

            echo "<pre>";
            echo "CH:<br />";
            print_r($ch);
            echo "<br />XML:<br />";
            print_r($xml);
            echo "</pre>";
            $xml = iconv('windows-1251', 'utf-8', $xml);
            preg_match("/<city>(.*?)<\/city>/", $xml, $a);
            $town = $a[1];
            Yii::app()->user->getState('currentTown', $town);
        } else {
            $town = Yii::app()->user->getState('currentTown');
        }
        return $town;
    }


    public static function detectTownLink($ip = null, $selector)
    {
        $town = self::detectTown($ip);
        if ($town) {
            $link = CHtml::link($town . "?", '', array('onclick' => "$('" . $selector . "').val('" . $town . "')", 'class' => 'suggest-link'));
            return $link;
        }
    }

    /**
     * Возвращает id города по номеру телефона
     * @param string $phoneNumber Номер телефона
     * @return integer ID города в базе. 0, если город в базе не найден
     */
    public static function detectTownIdByPhone($phoneNumber)
    {
        // приводим номер телефона к виду 7xxxxxxxxxx
        $phoneNumber = PhoneHelper::normalizePhone($phoneNumber);
        $htmlwebApiResponse = file_get_contents('http://htmlweb.ru/geo/api.php?json&telcod=' . $phoneNumber . '&charset=utf-8&api_key=' . Yii::app()->params['htmlwebApiKey']);
        // расшифровываем JSON-ответ от сервера в ассоциативный массив
        $htmlwebApiResponseArray = json_decode($htmlwebApiResponse, true);

        //self::printr($htmlwebApiResponseArray);
        $townName = $htmlwebApiResponseArray[0]['name'];
        Yii::log('Получение города по номеру телефона ' . $phoneNumber . '. Город: ' . $townName);

        $town = Town::model()->find('name="' . CHtml::encode($townName) . '"');

        if ($town) {
            return $town->id;
        } else {
            return 0;
        }
    }

    // функция преобразует дату из формата 2012-09-01 12:30:00 в Пн 1 сен. 2012 12:30
    public static function niceDate($date, $showTime = true, $showWeekday = true, $showYear = true)
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

    // функция возвращает время в формате hh:mm из даты yyyy-mm-dd hh:mm:ss
    public static function showTime($date)
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

    // функция принимает дату yyyy-mm-dd hh:mm:ss и возвращает массив из года, месяца, дня, часа, минуты, секунды, даты и времени
    public static function dateTimeArray($dateTime)
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

    public static function translit($name)
    { //$name=strtolower($name);
        $name = mb_strtolower($name, 'utf-8');
        $name = trim($name);
        $name = str_replace("а", "a", $name);
        $name = str_replace("б", "b", $name);
        $name = str_replace("в", "v", $name);
        $name = str_replace("г", "g", $name);
        $name = str_replace("д", "d", $name);
        $name = str_replace("е", "e", $name);
        $name = str_replace("ё", "e", $name);
        $name = str_replace("ж", "zh", $name);
        $name = str_replace("з", "z", $name);
        $name = str_replace("и", "i", $name);
        $name = str_replace("й", "j", $name);
        $name = str_replace("к", "k", $name);
        $name = str_replace("л", "l", $name);
        $name = str_replace("м", "m", $name);
        $name = str_replace("н", "n", $name);
        $name = str_replace("о", "o", $name);
        $name = str_replace("п", "p", $name);
        $name = str_replace("р", "r", $name);
        $name = str_replace("с", "s", $name);
        $name = str_replace("т", "t", $name);
        $name = str_replace("у", "u", $name);
        $name = str_replace("ф", "f", $name);
        $name = str_replace("х", "h", $name);
        $name = str_replace("ц", "c", $name);
        $name = str_replace("ч", "ch", $name);
        $name = str_replace("ш", "sch", $name);
        $name = str_replace("щ", "sh", $name);
        $name = str_replace("ъ", "j", $name);
        $name = str_replace("ы", "y", $name);
        $name = str_replace("ь", "", $name);
        $name = str_replace("э", "e", $name);
        $name = str_replace("ю", "yu", $name);
        $name = str_replace("я", "ya", $name);
        $name = str_replace(" ", "-", $name);
        $name = str_replace("_", "-", $name);
        return $name;
    }

    public static function printr($value)
    {
        echo "<pre>";
        print_r($value);
        echo "</pre>";
    }

    // функция пребразует дату в формате yyyy-mm-dd в формат dd-mm-yyyy и наоборот в зависимости от формата аргумента
    public static function invertDate($date)
    {
        if ($date == '') {
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
            return false;
        }
    }

    public static function getMonthsNames()
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

    public static function getWeekDays()
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
     * Обрезает строку до необходимой длины, сохраняя последнее слово целым
     * @param string $string Исходная строка
     * @param integer $len Максимальная длина итоговой строки
     * @param string $encode Кодировка строки
     */
    public static function cutString($string, $len, $encode = 'utf-8')
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

    public static function mb_ucfirst($string, $encoding = 'utf-8')
    {
        $strlen = mb_strlen($string, $encoding);
        $firstChar = mb_substr($string, 0, 1, $encoding);
        $then = mb_substr($string, 1, $strlen - 1, $encoding);
        return mb_strtoupper($firstChar, $encoding) . $then;
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
     * Удаляет из строки символы, не входящие в шаблон
     * @param string $string Строка
     * @param string $patternWhite Шаблон разрешенных символов
     * @return string
     */
    public static function filterString($string, $patternWhite = '0-9a-zA-Zа-яА-ЯёЁ\-., ')
    {
        return preg_replace('/[^0-9a-zA-Zа-яА-ЯёЁ\-., ]/', '', $string);
    }

    /**
     * @return string
     */
    public static function getUserIP(): string
    {
        $ip = '';
        if (isset($_SERVER['HTTP_X_REAL_IP'])) {
            $ip = $_SERVER['HTTP_X_REAL_IP'];
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        return $ip;
    }
}
