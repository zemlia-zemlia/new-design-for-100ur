<?php

// виджет для вывода статистики ответов

class MyAnswers extends CWidget
{
    public $template = 'default'; // представление виджета по умолчанию
    public $cacheTime = 600; // время кеширования по умолчанию

    public function run()
    {
        $answersTotalRow = Yii::app()->db->cache($this->cacheTime)->createCommand()
                ->select('COUNT(*) counter')
                ->from('{{answer}}')
                ->where('authorId=:authorId AND status IN(:statusNew, :statusPub)', [
                        ':authorId' => Yii::app()->user->id,
                        ':statusNew' => Answer::STATUS_NEW,
                        ':statusPub' => Answer::STATUS_PUBLISHED,
                    ])
                ->queryRow();
        $answersTotal = $answersTotalRow['counter'];

        $answersMonthRow = Yii::app()->db->cache($this->cacheTime)->createCommand()
                ->select('COUNT(*) counter')
                ->from('{{answer}}')
                ->where('authorId=:authorId AND status IN(:statusNew, :statusPub) AND datetime>NOW()-INTERVAL 30 DAY', [
                        ':authorId' => Yii::app()->user->id,
                        ':statusNew' => Answer::STATUS_NEW,
                        ':statusPub' => Answer::STATUS_PUBLISHED,
                    ])
                ->queryRow();
        $answersMonth = $answersMonthRow['counter'];

        $answersDayRow = Yii::app()->db->cache($this->cacheTime)->createCommand()
                ->select('COUNT(*) counter')
                ->from('{{answer}}')
                ->where('authorId=:authorId AND status IN(:statusNew, :statusPub) AND DATE(datetime)=DATE(NOW())', [
                        ':authorId' => Yii::app()->user->id,
                        ':statusNew' => Answer::STATUS_NEW,
                        ':statusPub' => Answer::STATUS_PUBLISHED,
                    ])
                ->queryRow();
        $answersDay = $answersDayRow['counter'];

        $this->render($this->template, [
            'answersTotal' => $answersTotal,
            'answersMonth' => $answersMonth,
            'answersDay' => $answersDay,
        ]);
    }
}
