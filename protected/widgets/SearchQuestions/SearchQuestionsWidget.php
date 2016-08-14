<?php
class SearchQuestionsWidget extends CWidget
{
    public $template = 'default'; // представление виджета по умолчанию
    
    public function run()
    {
        
        // модель для формы поиска по вопросам
        $searchModel = new QuestionSearch();
        
        $searchModel->townId = Yii::app()->user->townId;
                
        if($searchModel->townId) {
            $searchModel->townName = Town::getName($searchModel->townId);
        }
        
        $this->render($this->template, array('model'=>$searchModel));
    }
}
?>
