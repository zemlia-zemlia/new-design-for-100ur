<?php
class AutoTimestampBehavior extends CActiveRecordBehavior {
 
    /**
    * ѕоле которое содержит дату создани€ записи
    */
    public $created = 'create_time';
    /**
    * ѕоле которое содержит дату редактировани€ записи
    */
    public $modified = 'update_time';
 
 
    public function beforeValidate($on) {
        if ($this->Owner->isNewRecord)
            $this->Owner->{$this->created} = new CDbExpression('NOW()');
        else
            $this->Owner->{$this->modified} = new CDbExpression('NOW()');
 
        return true;    
    }
}