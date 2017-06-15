<?php
class LoginWidget extends CWidget
{
    public $template = 'default'; // представление виджета по умолчанию
    
    public function run()
    {
        
        $model = new LoginForm;
        
        $this->render($this->template, array(
            'model'         =>  $model,
        ));
    }
}
?>
