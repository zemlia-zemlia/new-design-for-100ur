<?php
class LoginWidget extends CWidget
{
    public $template = 'default'; // представление виджета по умолчанию
    
    public function run()
    {
        
        $model = new LoginForm;
        
        if(Yii::app()->user->role == User::ROLE_PARTNER) {
            $currentUser = User::model()->findByPk(Yii::app()->user->id);
        }
        
        $this->render($this->template, array(
            'model'         =>  $model,
            'currentUser'   =>  $currentUser,
        ));
    }
}
?>
