<?php

class CustomFuncs
{
    public static function numForms($num, $form1, $form2, $form5)
    {
        $num10 = $num%10;
         if($num>=10 && $num<20) return $form5;
          else if($num10==1) return $form1;
           else if($num10>1 && $num10<5) return $form2;
             else return $form5;
    }
    
    /**
     * Определение города пользователя по IP адресу
     * @param string $ip
     * @return Town город или NULL
     */
    public static function detectTown($ip=NULL)
    {
            $town = NULL;
            
            if(!Yii::app()->user->getState('currentTownId'))
            {
                // если принудительно не задан IP, берем текущий IP адрес
                if(empty($ip)) {
                    if($_SERVER['HTTP_X_REAL_IP']) {
                        $ip = $_SERVER['HTTP_X_REAL_IP'];
                    } else {
                        $ip = $_SERVER['REMOTE_ADDR'];
                    } 
                } 
                
//                echo "IP: " . $ip; 
                
                $data = "<ipquery><fields><all/></fields><ip-list><ip>".$ip."</ip></ip-list></ipquery>";
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
                $xml=iconv('windows-1251','utf-8',$xml);
                preg_match("/<city>(.*?)<\/city>/",$xml,$a);
                $townName =$a[1];
                //echo 'Город:' . $townName; exit;
                if($townName) {
                    $currentTown = Town::model()->findByAttributes(array('name'=>$townName));
                }
                return $currentTown;
                
            } 
            return $town;
    }
    
    // временная функция
    public static function detectTownTest($ip=NULL)
    {
            if(!Yii::app()->user->getState('currentTown'))
            {
                //if(empty($ip)) $ip=$_SERVER['REMOTE_ADDR'];
                if(empty($ip)) $ip=$_SERVER['HTTP_X_REAL_IP'];
                else $ip = "79.111.82.114";
                $data = "<ipquery><fields><all/></fields><ip-list><ip>".$ip."</ip></ip-list></ipquery>";
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
                if($curlError = curl_error($ch))
                {
                    echo "CURL Error: ".$curlError;
                }
                curl_close($ch);
                
                echo "<pre>";
                echo "CH:<br />";
                print_r($ch);
                echo "<br />XML:<br />";
                print_r($xml);
                echo "</pre>";
                $xml=iconv('windows-1251','utf-8',$xml);
                preg_match("/<city>(.*?)<\/city>/",$xml,$a);
                $town =$a[1];
                Yii::app()->user->getState('currentTown',$town);
                
            }
            else
            {
                $town = Yii::app()->user->getState('currentTown');
            }
            return $town;
    }
    
    
    public static function detectTownLink($ip=NULL, $selector)
    {
        $town = self::detectTown($ip);
        if($town)
        {
            $link = CHtml::link($town."?",'',array('onclick'=>"$('".$selector."').val('".$town."')", 'class'=>'suggest-link'));
            return $link;
        }
    }
    
    // функция преобразует дату из формата 2012-09-01 12:30:00 в Пн 1 сен. 2012 12:30
    public static function niceDate($date,$showTime=true,$showWeekday=true)
    {
        $monthsArray = array('','янв.', 'фев.','мар.','апр.','мая','июн.','июл.','авг.','сен.','окт.','ноя.','дек.');
        $weekDaysArray = array('Вс','Пн','Вт','Ср','Чт','Пт','Сб');
        $timestamp = strtotime($date);
        $weekdayNumber = date('w',$timestamp);
        $weekday = $weekDaysArray[$weekdayNumber];
        $dateTimeArray = self::dateTimeArray($date);
        
        $dateString = $dateTimeArray['day'] . " " . $monthsArray[$dateTimeArray['month']] . " " . $dateTimeArray['year'];
        
        if($showTime===true)
        {
            $dateString.= " ".$dateTimeArray['hours'].":".$dateTimeArray['minutes'];
        }
        if($showWeekday===true)
        {
           $dateString = $weekday . " " . $dateString;
        }
        return $dateString;
    }
    
    // функция возвращает время в формате hh:mm из даты yyyy-mm-dd hh:mm:ss
    public static function showTime($date)
    {
        if(stristr($date," "))
        {
            //это дата+время
            $dateTimeArray=explode(" ",$date);
            $time = $dateTimeArray[1];
            $timeArray = explode(":",$time);
            $hours = $timeArray[0];
            $minutes = $timeArray[1];
            $seconds = $timeArray[2];
            return $hours.":".$minutes;
        }
        else return NULL;
    }
    
    // функция принимает дату yyyy-mm-dd hh:mm:ss и возвращает массив из года, месяца, дня, часа, минуты, секунды, даты и времени
    public static function dateTimeArray($dateTime)
    {
        if(stristr($dateTime," "))
        {
            //это дата+время
            $dateTimeArray=explode(" ",$dateTime);
            $dateArray = explode("-",$dateTimeArray[0]);
            $time = $dateTimeArray[1];
            $year = (int)$dateArray[0];
            $month = (int)$dateArray[1];
            $day = (int)$dateArray[2];
            $timeArray = explode(":",$time);
            $hours = (int)$timeArray[0];
            $minutes = $timeArray[1];
            $seconds = $timeArray[2];
            $outputArray = Array(
                'year'=>$year,
                'month'=>$month,
                'day'=>$day,
                'hours'=>$hours,
                'minutes'=>$minutes,
                'seconds'=>$seconds,
                'date'=>$dateTimeArray[0],
                'time'=>$dateTimeArray[1]
            );
            return $outputArray;
        } elseif(stristr($dateTime,"-")) {
            $dateArray = explode("-",$dateTime);
            $year = (int)$dateArray[0];
            $month = (int)$dateArray[1];
            $day = (int)$dateArray[2];
            $outputArray = Array(
                'year'=>$year,
                'month'=>$month,
                'day'=>$day,
            );
            return $outputArray;
        } else {
            return NULL;
        }
    }

    public static function translit($name)
    { //$name=strtolower($name);
      $name = mb_strtolower($name, 'utf-8');
      $name=trim($name);
      $name=str_replace("а", "a", $name);
      $name=str_replace("б", "b", $name);
      $name=str_replace("в", "v", $name);
      $name=str_replace("г", "g", $name);
      $name=str_replace("д", "d", $name);
      $name=str_replace("е", "e", $name);
      $name=str_replace("ё", "e", $name);
      $name=str_replace("ж", "zh", $name);
      $name=str_replace("з", "z", $name);
      $name=str_replace("и", "i", $name);
      $name=str_replace("й", "j", $name);
      $name=str_replace("к", "k", $name);
      $name=str_replace("л", "l", $name);
      $name=str_replace("м", "m", $name);
      $name=str_replace("н", "n", $name);
      $name=str_replace("о", "o", $name);
      $name=str_replace("п", "p", $name);
      $name=str_replace("р", "r", $name);
      $name=str_replace("с", "s", $name);
      $name=str_replace("т", "t", $name);
      $name=str_replace("у", "u", $name);
      $name=str_replace("ф", "f", $name);
      $name=str_replace("х", "h", $name);
      $name=str_replace("ц", "c", $name);
      $name=str_replace("ч", "ch", $name);
      $name=str_replace("ш", "sch", $name);
      $name=str_replace("щ", "sh", $name);
      $name=str_replace("ъ", "j", $name);
      $name=str_replace("ы", "y", $name);
      $name=str_replace("ь", "", $name);
      $name=str_replace("э", "e", $name);
      $name=str_replace("ю", "yu", $name);
      $name=str_replace("я", "ya", $name);
      $name=str_replace(" ", "-", $name);
      $name=str_replace("_", "-", $name);
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
        if($date=='') return NULL;
        if(preg_match("/([0-9]{4})\-([0-9]{2})\-([0-9]{2})/",$date))
        {
            // аргумент $date - строка формата yyyy-mm-dd
            $dateArray = explode("-",$date);
            $year = $dateArray[0];
            $month = $dateArray[1];
            $day = $dateArray[2];
            
            // возвращаем дату в формате dd-mm-yyyy
            return $day."-".$month."-".$year;
        }
        else if(preg_match("/([0-9]{2})\-([0-9]{2})\-([0-9]{4})/",$date))
        {
            // аргумент $date - строка формата dd-mm-yyyy, разбиваем строку на части
            $dateArray = explode("-",$date);
            $year = $dateArray[2];
            $month = $dateArray[1];
            $day = $dateArray[0];
            // возвращаем дату в формате yyyy-mm-dd
            return $year."-".$month."-".$day;
        }
        else return false;
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

}
?>
