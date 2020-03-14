<?php
namespace repositories;

class QuestionRepositoryBack
{
    public static function countForModerate(){
       $allQuestion =  Yii::app()->db->createCommand()
           ->select('COUNT(*) counter')
           ->from('{{question}}')
           ->where('isModerated=0 AND status IN (:status1, :status2, :status3)', [':status1' => Question::STATUS_CHECK, ':status2' => Question::STATUS_PUBLISHED, ':status3' => Question::STATUS_MODERATED])
           ->queryRow();
       return  $allQuestion['counter'] ;

    }

    public static function countNoCat(){
        $questionsCountRows = Yii::app()->db->createCommand()
            ->select('q.id')
            ->from('{{question}} q')
            ->leftJoin('{{question2category}} q2c', 'q.id=q2c.qId')
            ->where('q2c.cId IS NULL AND q.status IN(' . Question::STATUS_PUBLISHED . ',' . Question::STATUS_CHECK . ', ' . Question::STATUS_MODERATED . ')')
            ->group('q.id')
            ->queryAll();
       return  count($questionsCountRows);

    }


}