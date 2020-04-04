<?php

namespace App\extensions\Logger;

use CHtml;
use Yii;

/**
 * Класс, читающий данные из лога событий.
 */
class LogReader
{
    public static function read($class, $subjectId, $limit = 20)
    {
        $command = Yii::app()->db->createCommand()
            ->select('*')
            ->from('{{log}}')
            ->order('id desc')
            ->limit($limit);

        if ($class) {
            $command->andWhere('class=:class', [':class' => $class]);
        }
        if ($subjectId) {
            $command->andWhere('subjectId=:subjectId', [':subjectId' => $subjectId]);
        }

        $rows = $command->queryAll();

        return $rows;
    }

    /**
     * Создает ссылку на сущность, привязанную к элементу лога.
     *
     * @param array $logRow
     * @return string
     */
    public static function createLink($logRow)
    {
        if ('' == $logRow['class'] || 0 == $logRow['subjectId']) {
            return '';
        }

        switch ($logRow['class']) {
            case 'Lead':
                return CHtml::link('Лид #' . $logRow['subjectId'], Yii::app()->createUrl('admin/lead/view', ['id' => $logRow['subjectId']]));

            case 'User':
                return CHtml::link('Польз. #' . $logRow['subjectId'], Yii::app()->createUrl('admin/user/view', ['id' => $logRow['subjectId']]));

            case 'Question':
                return CHtml::link('Вопрос. #' . $logRow['subjectId'], Yii::app()->createUrl('admin/question/view', ['id' => $logRow['subjectId']]));

            default:
                return '';
        }
    }
}
