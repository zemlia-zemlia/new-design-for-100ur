<?php

class UloginController extends Controller
{

    public function actionLogin()
    {
        if (isset($_POST['token'])) {
            $ulogin = new UloginModel();
            $ulogin->setAttributes($_POST);
            $ulogin->getAuthData();

            if ($ulogin->validate() && $ulogin->login()) {

                $questionId = Yii::app()->user->getState('question_id');
                if ($questionId) {
                    $question = Question::model()->findByPk($questionId);
                    $question->status = Question::STATUS_CHECK;
                    $question->authorId = Yii::app()->user->id;

                    $currentUser = Yii::app()->user->getModel();
                    $currentUser->phone = $question->phone;
                    $currentUser->townId = $question->townId;
                    $currentUser->townName = $question->town->name;

                    /** @var Question $question */
                    if ($question->save() && $currentUser->save()) {
                        $this->redirect(['question/view', 'id' => $question->id]);
                    }
                }

                $this->redirect(['/user']);
            }
        }
    }
}
