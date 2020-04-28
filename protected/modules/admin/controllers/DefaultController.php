<?php

use App\helpers\DateHelper;
use App\models\Lead;
use App\models\Money;
use App\modules\admin\controllers\AbstractAdminController;

class DefaultController extends AbstractAdminController
{

    public function actionIndex()
    {
        $statsService = new StatisticsService();

        // Первое число месяца год назад
        $startDate = (new DateTime(
            ((int) (new DateTime())->format('Y') - 1) . '-' .
            (new DateTime())->format('m') .
            '-01'
        ))->format('Y-m-d');

        $leadsRows = Yii::app()->db->createCommand()
            ->select('l.price summa, YEAR(l.question_date) year, MONTH(l.question_date) month, l.buyPrice, l.leadStatus')
            ->from('{{lead}} l')
            ->where('l.price != 0 AND l.question_date > :startDate', [
                ':startDate' => $startDate,
            ])
            ->order('id ASC')
            ->queryAll();

        $sumArray = []; // выручка
        $kolichArray = []; // количество
        $buySumArray = []; // затраты на покупку лидов
        $vipArray = []; // доходы с vip вопросов

        foreach ($leadsRows as $row) {
            if (Lead::LEAD_STATUS_SENT == $row['leadStatus']) {
                $sumArray[$row['year']][$row['month']] += $row['summa'];
                ++$kolichArray[$row['year']][$row['month']];
            }
            $buySumArray[$row['year']][$row['month']] += $row['buyPrice'];
            $vipArray[$row['year']][$row['month']] = 0; // предзаполнение массива выручки вип вопросов нулями
        }

        // статистика по VIP вопросам
        $vipRows = Yii::app()->db->createCommand()
            ->select('SUM(value) sum, MONTH(datetime) month, YEAR(datetime) year')
            ->from('{{money}}')
            ->where('type=:type AND direction=:direction AND datetime > :startDate', [
                ':type' => Money::TYPE_INCOME,
                ':direction' => 504,
                ':startDate' => $startDate,
            ])
            ->group('year, month')
            ->queryAll();

        foreach ($vipRows as $row) {
            $vipArray[$row['year']][$row['month']] = $row['sum'];
        }

        // извлекаем статистику кассы
        $moneyFlow = [];
        $showDirections = [502, 101, 2, 4];

        $moneyFlowRows = Yii::app()->db->createCommand()
            ->select('value, type, direction, MONTH(datetime) month, YEAR(datetime) year')
            ->from('{{money}}')
            ->where('isInternal = 0 AND datetime > :startDate', [
                'startDate' => $startDate,
            ])
            ->order('datetime')
            ->queryAll();

        // массив для хранения сумм расходов по месяцам
        $totalExpences = [];

        foreach ($moneyFlowRows as $row) {
            if (!in_array($row['direction'], $showDirections)) {
                continue;
            }
            if (Money::TYPE_INCOME == $row['type']) {
                $moneyFlow[$row['direction']][$row['year']][$row['month']] += $row['value'];
            } else {
                $moneyFlow[$row['direction']][$row['year']][$row['month']] -= $row['value'];
                $totalExpences[$row['year']][$row['month']] += $row['value'];
            }
        }

        $publishedQuestionsRows = Yii::app()->db->createCommand()
            ->select('COUNT(*) counter, DATE(createDate) date1')
            ->from('{{question}}')
            ->where('createDate>NOW()-INTERVAL 8 DAY AND status IN (2,4)')
            ->group('date1')
            ->queryAll();
        $publishedQuestionsCount = [];
        foreach ($publishedQuestionsRows as $row) {
            $publishedQuestionsCount[$row['date1']] = $row['counter'];
        }

//        array_shift($publishedQuestionsCount);  TODO зачем здесь это? у меня не было вопросов вообще, задал один, его здесь удаляет

        $leadsByTypesRows = Yii::app()->db->createCommand()
            ->select('COUNT(*) counter, type, DATE(question_date) date1')
            ->from('{{lead}}')
            ->where('question_date>NOW()-INTERVAL 30 DAY AND sourceId=:sourceId', [':sourceId' => 3])
            ->group('date1, type')
            ->queryAll();
        $leadsByTypes = [];
        $uniqueLeadDates = [];

        foreach ($leadsByTypesRows as $row) {
            $uniqueLeadDates[] = $row['date1'];
            $leadsByTypes[$row['type']][$row['date1']] = (int) $row['counter'];
        }

        $uniqueLeadDates = array_unique($uniqueLeadDates);

        $yuristActivityStats = $statsService->getYuristsActivityStats();
        $questionPublishedInRecentDays = $statsService->getPublishedQuestionsNumberInPeriod(30);
        $answersMadeInRecentDays = $statsService->getCountOfAnswersForRecentQuestions(30);
        $averageIntervalUntillAnswer = $statsService->getAverageDiffBetweenQuestionAndAnswer(30);

        /*
         * Получение статистики по лидам источника 100 Юристов
         */
        $monthAgoDate = (new DateTime())->sub(new DateInterval('P30D'))->format('Y-m-d');
        $stat100yuristovRows = Yii::app()->db->createCommand()
            ->select('DATE(l.question_date) lead_date, COUNT(*) counter')
            ->from('{{lead}} l')
            ->where('DATE(l.question_date) >= :startDate AND l.sourceId = 3 AND l.leadStatus != :brak AND NOT (l.leadStatus = :double AND l.buyerId = 0)', [
                ':double' => Lead::LEAD_STATUS_DUPLICATE,
                ':brak' => Lead::LEAD_STATUS_BRAK,
                ':startDate' => $monthAgoDate,
            ])
            ->group('lead_date')
            ->order('lead_date')
            ->queryAll();
        $stat100yuristov = [];

        foreach ($stat100yuristovRows as $row) {
            $stat100yuristov[$row['lead_date']] = $row['counter'];
        }

        $stat100yuristov = DateHelper::fillEmptyDatesArrayByDefaultValues($stat100yuristov);

        ksort($stat100yuristov);

        $this->render('index', [
            'sumArray' => $sumArray,
            'kolichArray' => $kolichArray,
            'buySumArray' => $buySumArray,
            'moneyFlow' => $moneyFlow,
            'totalExpences' => $totalExpences,
            'publishedQuestionsCount' => $publishedQuestionsCount,
            'leadsByTypes' => $leadsByTypes,
            'uniqueLeadDates' => $uniqueLeadDates,
            'yuristActivityStats' => $yuristActivityStats,
            'stat100yuristov' => $stat100yuristov,
            'questionPublishedInRecentDays' => $questionPublishedInRecentDays,
            'answersMadeInRecentDays' => $answersMadeInRecentDays,
            'averageIntervalUntillAnswer' => $averageIntervalUntillAnswer,
        ]);
    }

    /**
     * Тестирование отправки писем через SMTP и через встроенную функцию mail().
     */
    public function actionTestMail()
    {
        echo 'Тестируем отправку письма';

        // первое письмо отправим через встроенную функцию
        $testMail = new GTMail(GTMail::TRANSPORT_TYPE_SENDMAIL);
        $testMail->subject = 'Проверка работы почты';
        $testMail->email = 'misha-sunsetboy@yandex.ru';
        $testMail->message = 'Проверка отправки почты';

        if ($testMail->sendMail()) {
            echo 'Письмо через встроенную функцию отправлено';
        } else {
            echo 'Письмо через встроенную функцию НЕ отправлено';
        }

        // первое письмо отправим через встроенную функцию
        $testMail = new GTMail(GTMail::TRANSPORT_TYPE_SMTP);
        $testMail->subject = 'Проверка работы почты';
        $testMail->email = 'misha-sunsetboy@yandex.ru';
        $testMail->message = 'Проверка отправки почты через SMTP';

        if ($testMail->sendMail()) {
            echo 'Письмо через SMTP отправлено';
        } else {
            echo 'Письмо через SMTP НЕ отправлено';
        }
    }

    public function actionInfo()
    {
        phpinfo();
    }

    public function actionClient()
    {
        var_dump(Yii::app()->params['webmaster100yuristovId']);
    }
}
