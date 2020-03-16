<?php

use App\models\Question;

class QuestionTitleCommand extends CConsoleCommand
{
    public function actionIndex()
    {
        $criteria = new CDbCriteria();
        $criteria->addColumnCondition(['status' => Question::STATUS_NEW]);
        $criteria->addColumnCondition(['title' => '']);
//        $criteria->limit = 500;

        $questions = Question::model()->findAll($criteria);
        echo sizeof($questions) . ' found';
        foreach ($questions as $question) {
            $question->status = Question::STATUS_MODERATED;
            $question->save();
        }
    }
}
