<?php

use App\models\Question;
use App\models\QuestionSearch;
use App\models\Town;

class SearchQuestionsWidget extends CWidget
{
    public $template = 'default'; // представление виджета по умолчанию

    public function run()
    {
        // модель для формы поиска по вопросам
        $searchModel = new QuestionSearch();

        if (isset($_GET['QuestionSearch'])) {
            $searchModel->attributes = $_GET['QuestionSearch'];
        }

        $searchModel->townId = Yii::app()->user->townId;

        if ($searchModel->townId) {
            $searchModel->townName = Town::getName($searchModel->townId);
        }

        $randomQuestionId = Question::getRandomId(Yii::app()->user->getModel());

        $this->render($this->template, [
                'model' => $searchModel,
                'randomQuestionId' => $randomQuestionId,
        ]);
    }
}
