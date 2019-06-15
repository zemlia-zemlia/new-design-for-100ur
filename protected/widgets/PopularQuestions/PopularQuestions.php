<?php

/**
 * Отображение недавних вопросов с максимальным числом ответов
 * Class PopularQuestions
 */
class PopularQuestions extends CWidget
{
    public $template = 'default'; // представление виджета по умолчанию
    public $cacheTime = 600; // время кеширования по умолчанию
    public $showPayed = false; // показывать платные вопросы

    public function run()
    {
        /*
            SELECT q.id, q.publishDate, q.title, a.id answerId, a.answerText, c.text, COUNT(*) counter
            FROM 100_question q
            LEFT JOIN 100_answer a ON q.id = a.questionId
            LEFT JOIN 100_comment c ON a.id = c.objectId
            WHERE c.type=4 AND c.status IN (0,1) AND q.status IN (2,4) AND q.publishDate > NOW()-INTERVAL 7 DAY
            GROUP BY a.id
            order by q.id desc
         */
        $priceCondition = ($this->showPayed === true) ? 'price > 0' : 'price = 0';
        $questionsRows = Yii::app()->db->cache($this->cacheTime)->createCommand()
            ->select('q.id, q.title, q.createDate, q.price, a.id answerId, COUNT(*) counter')
            ->from('{{question}} q')
            ->leftJoin('{{answer}} a', 'q.id=a.questionId')
            ->leftJoin('{{comment}} c', 'a.id = c.objectId')
            ->where('q.createDate > NOW() - INTERVAL 7 DAY AND ' . $priceCondition . ' AND a.status!=:status AND c.type=:commentType AND c.status!=:commentSpam', [
                ':status' => Answer::STATUS_SPAM,
                ':commentType' => Comment::TYPE_ANSWER,
                ':commentSpam' => Comment::STATUS_SPAM,
            ])
            ->group('a.id')
            ->order('q.id DESC')
            ->queryAll();

        $questions = [];

        foreach ($questionsRows as $row) {
            $questions[$row['id']]['title'] = $row['title'];
            $questions[$row['id']]['id'] = $row['id'];
            $questions[$row['id']]['createDate'] = $row['createDate'];
            $questions[$row['id']]['price'] = $row['price'];
            $questions[$row['id']]['answersCount']++;
            $questions[$row['id']]['commentsCount'] += $row['counter'];
        }

        $questions = array_slice($questions, 0, 10);

        $this->render($this->template, [
            'questions' => $questions,
        ]);
    }
}
