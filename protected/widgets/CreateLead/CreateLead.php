<?php
class CreateLead extends CWidget
{
    public $template = 'default'; // представление виджета по умолчанию
    
    public function run()
    {
        $model = new Lead;
        
        $this->render($this->template, array('model'=>$model));
    }
}
