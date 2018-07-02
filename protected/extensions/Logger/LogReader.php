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
    
    /**
     * Создает ссылку на сущность, привязанную к элементу лога
     * @param type $logRow
     */
    public static function createLink($logRow)
    {
        if($logRow['class'] == '' || $logRow['subjectId'] == 0) {
            return '';
        }
        
        switch($logRow['class']) {
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

