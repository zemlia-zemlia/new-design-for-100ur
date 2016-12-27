<?php
class SearchQuestionsWidget extends CWidget
{
    public $template = 'default'; // представление виджета по умолчанию
    
    public function run()
    {
        
        // модель для формы поиска по вопросам
        $searchModel = new QuestionSearch();
        
        if(isset($_GET['QuestionSearch'])) {
            $searchModel->attributes = $_GET['QuestionSearch'];
        }
        
        $searchModel->townId = Yii::app()->user->townId;
                
        if($searchModel->townId) {
            $searchModel->townName = Town::getName($searchModel->townId);
        }
        
        $randomQuestionId = Question::getRandomId();
        
        $this->render($this->template, array(
                'model'             =>  $searchModel,
                'randomQuestionId'  =>  $randomQuestionId,
        ));
    }
}
?>
