<?php

class DefaultController extends Controller
{
    public $layout='//admin/main';

    public function actionIndex()
    {
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
        
        foreach($leadsRows as $row) {
            if($row['leadStatus'] == Lead100::LEAD_STATUS_SENT) {
                $sumArray[$row['year']][$row['month']] += $row['summa'];
                $kolichArray[$row['year']][$row['month']] ++ ;
            }
            $buySumArray[$row['year']][$row['month']] += $row['buyPrice'];
        }
            
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
		foreach($answerStatRows as $row) {
                    if($row['delta']<4) {
                            $fastQuestionsCount++;
                    }
		}
		if($questionsCount>0){
                    $fastQuestionsRatio = round(($fastQuestionsCount/$questionsCount)*100,1);
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
        
        foreach($moneyFlowRows as $row) {
            if(!in_array($row['direction'], $showDirections)) {
                continue;
            }
            if($row['type'] == Money::TYPE_INCOME) {
                $moneyFlow[$row['direction']][$row['year']][$row['month']] += $row['value'];
            } else {
                $moneyFlow[$row['direction']][$row['year']][$row['month']] -= $row['value'];
                $totalExpences[$row['year']][$row['month']] += $row['value'];
            }
        }
        
        // воронка вопросов по статусам
        // SELECT status, count(*) counter FROM `100_question` WHERE createDate>NOW()-INTERVAL 90 DAY GROUP BY status
        
        $questionStatuses = array();
        $questionStatusesRows = Yii::app()->db->createCommand()
                ->select("status, count(*) counter")
                ->from("{{question}}")
                ->where("createDate>NOW()-INTERVAL 90 DAY")
                ->group("status")
                ->queryAll();
        foreach ($questionStatusesRows as $row) {
            $questionStatuses[$row['status']] = $row['counter'];
        }
        
        // найдем статистику вопросов с ответами за последние 90 дней
        /*
         *  EXPLAIN SELECT q.id, COUNT(*) counter FROM `100_question` q
            LEFT JOIN `100_answer` a ON a.questionId = q.id
            WHERE q.createDate > NOW()-INTERVAL 90 DAY AND a.id IS NOT NULL
            GROUP BY q.id 
            ORDER BY counter DESC
         */
        $questionWithAnswerRows = Yii::app()->db->createCommand()
                ->select("q.id")
                ->from("{{question}} q")
                ->leftJoin("{{answer}} a", "a.questionId = q.id")
                ->where("q.createDate > NOW()-INTERVAL 90 DAY AND a.id IS NOT NULL")
                ->group("q.id")
                ->queryAll();
        $questionsWithAnswersCount = sizeof($questionWithAnswerRows);
        
        
        // соберем статистику по статусам вопросов по месяцам за последние 12 недель
        /*
         * SELECT WEEK(createDate) week, COUNT(*) counter, status, email FROM `100_question`
            WHERE createDate > NOW()-INTERVAL 12 WEEK
            GROUP BY week, status, email
            ORDER BY createDate asc
         */
        
        $questionByWeekArray = array();
        $questionByWeekRows = Yii::app()->db->createCommand()
                ->select('WEEK(createDate) week, COUNT(*) counter, status, email')
                ->from('{{question}}')
                ->where('createDate > NOW()-INTERVAL 12 WEEK AND status!=:spam', array(':spam' => Question::STATUS_SPAM))
                ->group('week, status, email')
                ->order('createDate asc')
                ->queryAll();
        
        //CustomFuncs::printr($questionByWeekRows);
        //exit;
        
      
        foreach($questionByWeekRows as $row) {
            
            $questionByWeekArray[$row['week']][$row['status']]['total'] += $row['counter'];
            $questionByWeekArray[$row['week']]['total'] += $row['counter'];
            if($row['status'] == Question::STATUS_NEW) {
                if($row['email'] == '') {
                    $questionByWeekArray[$row['week']][$row['status']]['no_email'] += $row['counter'];
                } else {
                    $questionByWeekArray[$row['week']][$row['status']]['with_email'] += $row['counter'];
                }
            }
        }
        
        // заполним отсутствующую статистику нулями для красивого графика
        foreach($questionByWeekArray as $week=>$weekData) {
            for($status = 0; $status<=5; $status++) {
                if(!$weekData[$status]) {
                    $questionByWeekArray[$week][$status]['total'] = 0;
                }
                if($status == 0) {
                    if(!$weekData[$status]['no_email']) {
                        $questionByWeekArray[$week][$status]['no_email'] = 0;
                    }
                    if(!$weekData[$status]['with_email']) {
                        $questionByWeekArray[$week][$status]['with_email'] = 0;
                    }
                }
                
            }
        }
        
        //CustomFuncs::printr($questionByWeekArray);
        
        
        
        $this->render('index', array(
            'sumArray'                  =>  $sumArray,
            'kolichArray'               =>  $kolichArray,
            'buySumArray'               =>  $buySumArray,
            'fastQuestionsRatio'        =>  $fastQuestionsRatio,
            'moneyFlow'                 =>  $moneyFlow,
            'totalExpences'             =>  $totalExpences,
            'questionStatuses'          =>  $questionStatuses,
            'questionsWithAnswersCount' =>  $questionsWithAnswersCount,
            'questionByWeekArray'       =>  $questionByWeekArray,
        ));
    }
}