<?php

/**
 * Класс, читающий данные из лога событий
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
        
        if($class) {
            $command->andWhere('class=:class', [':class' => $class]);
        }
        if($subjectId) {
            $command->andWhere('subjectId=:subjectId', [':subjectId' => $subjectId]);
        }
        
        $rows = $command->queryAll();
        
        return $rows;
    }
}

