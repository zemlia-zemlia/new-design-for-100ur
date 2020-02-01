<?php

/*
 * команда обходит все вопросы и генерирует для них заголовки, после чего сохраняет вопрос
 */
class AllQuestionsTitlesCommand extends CConsoleCommand
{
    public function actionIndex()
    {
        $criteria = new CDbCriteria();
        $criteria->limit = 50;
        $criteria->order = "id";
        $criteria->addColumnCondition(array('title'=>''));
        
        $questions = Question::model()->findAll($criteria);
        echo sizeof($questions) . " found\n";
        foreach ($questions as $question) {
            echo $question->id . "\n";
            echo $question->questionText . "\n";
            $question->formTitle();
            echo $question->title . "\n";
            if ($question->save()) {
                echo " saved\n";
            } else {
                echo " NOT saved\n";
                print_r($question->errors);
            }
            /*$question = null;
            unset($question);*/
        }
    }
}
