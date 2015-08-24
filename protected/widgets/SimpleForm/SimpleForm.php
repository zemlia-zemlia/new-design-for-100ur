<?php
class SimpleForm extends CWidget
{
    public $template = 'default'; // представление виджета по умолчанию
    
    public function run()
    {
        
        $model = new Question;
        
        $this->render($this->template, array('model'=>$model));
    }
}
?>
