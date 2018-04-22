<?php

class DefaultController extends Controller {

    public $layout = '//admin/main';

    public function actionIndex() {
        $leadsRows = Yii::app()->db->createCommand()
                ->select('l.price summa, YEAR(l.question_date) year, MONTH(l.question_date) month, l.buyPrice, l.leadStatus')
                ->from('{{lead100}} l')
                ->where('l.price != 0')
                ->order('id ASC')
                ->queryAll();

        //CustomFuncs::printr($leadsRows);

        $sumArray = array(); // выручка
        $kolichArray = array(); // количество
        $buySumArray = array(); // затраты на покупку лидов
        $vipArray = array(); // доходы с vip вопросов

        foreach ($leadsRows as $row) {
            if ($row['leadStatus'] == Lead100::LEAD_STATUS_SENT) {
                $sumArray[$row['year']][$row['month']] += $row['summa'];
                $kolichArray[$row['year']][$row['month']] ++;
            }
            $buySumArray[$row['year']][$row['month']] += $row['buyPrice'];
            $vipArray[$row['year']][$row['month']] = 0; // предзаполнение массива выручки вип вопросов нулями
        }


        // статистика по VIP вопросам
        $vipRows = Yii::app()->db->createCommand()
                ->select('SUM(value) sum, MONTH(datetime) month, YEAR(datetime) year')
                ->from('{{money}}')
                ->where('type=:type AND direction=:direction', array(
                    ':type' => Money::TYPE_INCOME,
                    ':direction' => 504,
                ))
                ->group('year, month')
                ->queryAll();

        foreach ($vipRows as $row) {
            $vipArray[$row['year']][$row['month']] = $row['sum'];
        }
        //CustomFuncs::printr($vipArray);
//        CustomFuncs::printr($sumArray);
//        CustomFuncs::printr($kolichArray);
//        CustomFuncs::printr($buySumArray);
        // статистика времени, которое требуется на ответ на вопрос
        // показывает, на какой процент вопросов ответ был дан меньше чем за 4 часа (за последние 30 дней)
        /*
          Выборка статистики по времени между ответом и вопросом за последний месяц
          SELECT a.id, a.datetime answer_time, q.id, q.publishDate q_time, TIMESTAMPDIFF(HOUR, q.publishDate, a.datetime) delta FROM `100_answer` a
          LEFT JOIN `100_question` q ON q.id = a.questionId
          WHERE a.datetime > (NOW() - INTERVAL 30 DAY)
          GROUP BY q.id
          ORDER BY delta  asc
         */

        $answerStatRows = Yii::app()->db->createCommand()
                ->select('TIMESTAMPDIFF(HOUR, q.publishDate, a.datetime) delta')
                ->from('{{answer}} a')
                ->leftJoin('{{question}} q', 'q.id = a.questionId')
                ->where('a.datetime > (NOW() - INTERVAL 30 DAY)')
                ->group('q.id')
                ->order('delta ASC')
                ->queryAll();
        $questionsCount = sizeof($answerStatRows);
        $fastQuestionsCount = 0;
        foreach ($answerStatRows as $row) {
            if ($row['delta'] < 4) {
                $fastQuestionsCount++;
            }
        }
        if ($questionsCount > 0) {
            $fastQuestionsRatio = round(($fastQuestionsCount / $questionsCount) * 100, 1);
        } else {
            $fastQuestionsRatio = 0;
        }

        // извлекаем статистику кассы  
        $moneyFlow = array();
        $showDirections = array(502, 101, 3, 2, 4);
        $moneyFlowRows = Yii::app()->db->createCommand()
                ->select('value, type, direction, MONTH(datetime) month, YEAR(datetime) year')
                ->from('{{money}}')
                ->where('isInternal = 0')
                ->order('datetime')
                ->queryAll();

        // массив для хранения сумм расходов по месяцам
        $totalExpences = array();

        foreach ($moneyFlowRows as $row) {
            if (!in_array($row['direction'], $showDirections)) {
                continue;
            }
            if ($row['type'] == Money::TYPE_INCOME) {
                $moneyFlow[$row['direction']][$row['year']][$row['month']] += $row['value'];
            } else {
                $moneyFlow[$row['direction']][$row['year']][$row['month']] -= $row['value'];
                $totalExpences[$row['year']][$row['month']] += $row['value'];
            }
        }

        $publishedQuestionsRows = Yii::app()->db->createCommand()
                ->select("COUNT(*) counter, DATE(createDate) date1")
                ->from("{{question}}")
                ->where("createDate>NOW()-INTERVAL 8 DAY AND status IN (2,4)")
                ->group('date1')
                ->queryAll();
        $publishedQuestionsCount = array();
        foreach ($publishedQuestionsRows as $row) {
            $publishedQuestionsCount[$row['date1']] = $row['counter'];
        }

        array_shift($publishedQuestionsCount);

        $leadsByTypesRows = Yii::app()->db->createCommand()
                ->select("COUNT(*) counter, type, DATE(question_date) date1")
                ->from('{{lead100}}')
                ->where('question_date>NOW()-INTERVAL 30 DAY AND sourceId=:sourceId', array(':sourceId' => 3))
                ->group('date1, type')
                ->queryAll();
        $leadsByTypes = array();
        $uniqueLeadDates = array();

        foreach ($leadsByTypesRows as $row) {
            $uniqueLeadDates[] = $row['date1'];
            $leadsByTypes[$row['type']][$row['date1']] = (int) $row['counter'];
        }

        $uniqueLeadDates = array_unique($uniqueLeadDates);
        //CustomFuncs::printr($leadsByTypes);
        
        $yuristActivityStatsRows = Yii::app()->db->createCommand()
                ->select('COUNT(*) counter, DATE(lastActivity) lastDate')
                ->from('{{user}}')
                ->where('role=:role', [':role' => User::ROLE_JURIST])
                ->group('lastDate')
                ->order('lastDate DESC')
                ->limit(10)
                ->queryAll();
        $yuristActivityStats = [];
        
        foreach ($yuristActivityStatsRows as $row) {
            $yuristActivityStats[$row['lastDate']] = $row['counter'];
        }
        
        //CustomFuncs::printr($yuristActivityStats);

        $this->render('index', array(
            'sumArray'                  => $sumArray,
            'kolichArray'               => $kolichArray,
            'buySumArray'               => $buySumArray,
            'fastQuestionsRatio'        => $fastQuestionsRatio,
            'moneyFlow'                 => $moneyFlow,
            'totalExpences'             => $totalExpences,
            'questionStatuses'          => $questionStatuses,
            'publishedQuestionsCount'   => $publishedQuestionsCount,
            'leadsByTypes'              => $leadsByTypes,
            'uniqueLeadDates'           => $uniqueLeadDates,
            'yuristActivityStats'       => $yuristActivityStats,
        ));
    }
    
    /**
     * Тестирование отправки писем через SMTP и через встроенную функцию mail()
     */
    public function actionTestMail()
    {
        echo 'Тестируем отправку письма';
        
        // первое письмо отправим через встроенную функцию
        $testMail = new GTMail(false);
        $testMail->subject = 'Проверка работы почты';
        $testMail->email = 'misha-sunsetboy@yandex.ru';
        $testMail->message = 'Проверка отправки почты';
        
        if($testMail->sendMail()) {
            echo 'Письмо через встроенную функцию отправлено';
        } else {
            echo 'Письмо через встроенную функцию НЕ отправлено';
        }
        
        // первое письмо отправим через встроенную функцию
        $testMail = new GTMail(true);
        $testMail->subject = 'Проверка работы почты';
        $testMail->email = 'misha-sunsetboy@yandex.ru';
        $testMail->message = 'Проверка отправки почты через SMTP';
        
        if($testMail->sendMail()) {
            echo 'Письмо через SMTP отправлено';
        } else {
            echo 'Письмо через SMTP НЕ отправлено';
        }
    }

}
