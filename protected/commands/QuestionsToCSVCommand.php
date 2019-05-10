<?php
/**
 * Выгрузка вопросов для дальнейшего анализа
 * Class QuestionsToCSVCommand
 */

class QuestionsToCSVCommand extends CConsoleCommand
{
    public function actionIndex()
    {
//        SELECT q.id, q.createDate, q.questionText, q.status, c.name, c.id
//        FROM 100yuristov.100_question q
//        LEFT JOIN 100_question2category q2c ON q.id = q2c.qId
//        LEFT JOIN 100_questionCategory c ON c.id = q2c.cId
//        GROUP BY q.id
//        LIMIT 10000

        $command = Yii::app()->db->createCommand()
            ->select('q.id, q.createDate, q.questionText, q.status, c.name cat_name, c.id cat_id')
            ->from("{{question}} q")
            ->leftJoin("{{question2category}} q2c", 'q.id = q2c.qId')
            ->leftJoin("{{questionCategory}} c", 'c.id = q2c.cId')
            ->where("c.id IS NOT NULL")
            ->group('q.id')
            ->limit(10000);

        $questions = $command->queryAll();
        var_dump($questions);

        $fp = fopen(__DIR__. '/output/questions.csv', 'w');
        $order   = ["\r\n", "\n", "\r", "\""];
        $replace = '';
        foreach ($questions as $question) {
            $question['questionText'] = str_replace($order, $replace, $question['questionText']);
//            $question['questionText'] = nl2br($question['questionText']);
            fputcsv($fp, $question);
        }

        fclose($fp);
    }

    public function actionTypes()
    {
        $command = Yii::app()->db->createCommand()
            ->select('q.id, q.questionText, q.status')
            ->from("{{question}} q")
            ->where("q.status NOT IN (0, 5)")
            ->limit(100000);

        $questions = $command->queryAll();
        $fp = fopen(__DIR__. '/output/questions_status.csv', 'w');
        $order   = ["\r\n", "\n", "\r", "\""];
        $replace = '';
        foreach ($questions as $question) {
            $question['questionText'] = str_replace($order, $replace, $question['questionText']);
            fputcsv($fp, $question);
        }
    }
}