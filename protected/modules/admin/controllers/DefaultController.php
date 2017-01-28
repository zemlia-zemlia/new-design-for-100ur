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
                $sumArray[$row['month'] . '.' . $row['year']] += $row['summa'];
                $kolichArray[$row['month'] . '.' . $row['year']] ++ ;
            }
            $buySumArray[$row['month'] . '.' . $row['year']] += $row['buyPrice'];
        }
            
//        CustomFuncs::printr($sumArray);
//        CustomFuncs::printr($kolichArray);
//        CustomFuncs::printr($buySumArray);
        
        $this->render('index', array(
            'sumArray'      =>  $sumArray,
            'kolichArray'   =>  $kolichArray,
            'buySumArray'   =>  $buySumArray,
        ));
    }
}