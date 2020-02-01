<?php

class QuestionTitleCommand extends CConsoleCommand
{
    public function actionIndex()
    {
        $criteria = new CDbCriteria();
        $criteria->addColumnCondition(array('status'=>Question::STATUS_NEW));
        $criteria->addColumnCondition(array('title'=>''));
//        $criteria->limit = 500;
        
        $questions = Question::model()->findAll($criteria);
        echo sizeof($questions) . " found";
        foreach ($questions as $question) {
            $question->status = Question::STATUS_MODERATED;
            $question->save();
        }
    }
}
