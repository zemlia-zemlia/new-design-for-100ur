<?php
class AutoTimestampBehavior extends CActiveRecordBehavior {
 
    /**
    * ���� ������� �������� ���� �������� ������
    */
    public $created = 'create_time';
    /**
    * ���� ������� �������� ���� �������������� ������
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