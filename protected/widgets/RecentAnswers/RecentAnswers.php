<?php

/**
 *  виджет для вывода последних ответов, юристы должны быть уникальными
 */

class RecentAnswers extends CWidget
{
    public $template = 'default'; // представление виджета по умолчанию
    public $cacheTime = 0; // время кеширования
    public $limit = 6;
    
    public function run()
    {
        $dateStart = date('Y-m-d', time()-60*60*24*130);
        // выберем ответы за последний месяц, с вопросами и авторами
        $answersRows = Yii::app()->db->cache($this->cacheTime)->createCommand()
                ->select('q.title questionTitle, q.id questionId, q.price questionPrice, q.payed questionPayed, a.answerText, a.datetime answerTime, a.authorId, u.name authorName, u.name2 authorName2, u.lastName authorLastName, u.lastActivity')
                ->from('{{answer}} a')
                ->leftJoin('{{user}} u', 'a.authorId = u.id')
                ->leftJoin('{{question}} q', 'q.id = a.questionId')
                ->where('a.datetime>:dateStart AND a.status!=2 AND a.videoLink="" AND u.active100=1', array(':dateStart' => $dateStart))
                ->order('answerTime DESC')
                ->queryAll();
//        $answersIds = Yii::app()->db->cache($this->cacheTime)->createCommand()
//                ->select('a.id') 
//                ->from('{{answer}} a')
//                ->leftJoin('{{user}} u', 'a.authorId = u.id')
//                ->where('a.datetime>:dateStart AND a.status!=2 AND a.videoLink="" AND u.active100=1', array(':dateStart' => $dateStart))
//                ->group('a.authorId')
//                ->order('a.datetime DESC')
//                ->limit($this->limit)
//                ->queryColumn();
//        //CustomFuncs::printr($answersIds);
//        
//        $answersRows = Yii::app()->db->createCommand()
//                ->select('q.title questionTitle, q.id questionId, q.price questionPrice, q.payed questionPayed, a.answerText, a.datetime answerTime, a.authorId, u.name authorName, u.name2 authorName2, u.lastName authorLastName, u.lastActivity')
//                ->from('{{answer}} a')
//                ->leftJoin('{{user}} u', 'a.authorId = u.id')
//                ->leftJoin('{{question}} q', 'q.id = a.questionId')
//                ->where(array('in', 'a.id', $answersIds))
//                ->order('answerTime DESC')
//                ->queryAll();
//        //CustomFuncs::printr($answersRows);
//        //exit;
       
        $answers = array();
        $authorId = 0;
        
        // нам нужны ответы от уникальных авторов, выберем их в массив $answers
        foreach($answersRows as $row) {
            if($row['authorId']!=$authorId && !$answers[$row['authorId']]) {
                $answers[$row['authorId']] = $row;
                $authorId = $row['authorId'];
            }
            // в выходном массиве нужно не более $limit элементов
            if(sizeof($answers) == $this->limit) {
                break;
            }
        }
                            
        $this->render($this->template, array(
            'answers'  =>  $answers,
        ));
    }
}
?>