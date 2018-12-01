<?php

/**
 * Вывод блока с записями лога событий
 */
class LogReaderWidget extends CWidget
{
    public $template = 'default'; // представление виджета по умолчанию
    public $class;
    public $subjectId;
    public $limit = 20;
    
    public function run()
    {
        $records = LogReader::read($this->class, $this->subjectId, $this->limit);
                
        $this->render($this->template, array(
            'records'   => $records,
        ));
    }
}

