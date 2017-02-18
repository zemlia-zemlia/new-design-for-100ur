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
                ->order('datetime')
                ->queryAll();
        
        
        
        foreach($moneyFlowRows as $row) {
            if(!in_array($row['direction'], $showDirections)) {
                continue;
            }
            if($row['type'] == Money::TYPE_INCOME) {
                $moneyFlow[$row['direction']][$row['year']][$row['month']] += $row['value'];
            } else {
                $moneyFlow[$row['direction']][$row['year']][$row['month']] -= $row['value'];
            }
        }
        
        $this->render('index', array(
            'sumArray'              =>  $sumArray,
            'kolichArray'           =>  $kolichArray,
            'buySumArray'           =>  $buySumArray,
            'fastQuestionsRatio'    =>	$fastQuestionsRatio,
            'moneyFlow'             =>	$moneyFlow,
        ));
    }
}