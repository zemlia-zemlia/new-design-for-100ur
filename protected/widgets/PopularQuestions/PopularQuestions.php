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
    public $intervalDays = 15; // период выборки

    public function run()
    {
        $priceCondition = ($this->showPayed === true) ? 'price > 0' : 'price = 0';
        $questionsRows = Yii::app()->db->cache($this->cacheTime)->createCommand()
            ->select('q.id, q.title, q.createDate, q.price, a.id answerId, COUNT(a.id) counter')
            ->from('{{question}} q')
            ->leftJoin('{{answer}} a', 'q.id=a.questionId')
            ->where('q.createDate > NOW() - INTERVAL :interval DAY AND ' . $priceCondition . ' AND a.status!=:status', [
                ':status' => Answer::STATUS_SPAM,
                ':interval' => $this->intervalDays,
            ])
            ->group('q.id')
            ->order('q.id DESC')
            ->queryAll();
        $questions = [];

        foreach ($questionsRows as $row) {
            $questions[$row['id']]['title'] = $row['title'];
            $questions[$row['id']]['id'] = $row['id'];
            $questions[$row['id']]['createDate'] = $row['createDate'];
            $questions[$row['id']]['price'] = $row['price'];
            $questions[$row['id']]['answersCount'] = $row['counter'];
        }

        $questions = array_slice($questions, 0, 10);

        $this->render($this->template, [
            'questions' => $questions,
        ]);
    }
}
