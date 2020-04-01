<?php

// виджет для вывода произвольного вопроса

use App\models\Question;

class RandomQuestion extends CWidget
{
    public $template = 'default'; // представление виджета по умолчанию

    public function run()
    {
        $question = Yii::app()->db->createCommand()
                ->select('q.id id, questionText, townId, authorName')
                ->from('{{question q}}')
                ->leftJoin('{{answer a}}', 'a.questionId = q.id')
                ->where('q.status=:status AND a.id IS NULL', [':status' => Question::STATUS_PUBLISHED])
                ->order('RAND()')
                ->limit(1)
                ->queryRow();

        $this->render($this->template, [
            'question' => $question,
        ]);
    }
}
