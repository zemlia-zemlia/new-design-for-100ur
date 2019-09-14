<?php

class UloginController extends Controller
{

    public function actionLogin()
    {

        if (isset($_POST['token'])) {
            $ulogin = new UloginModel();
            $ulogin->setAttributes($_POST);
            $ulogin->getAuthData();

            if ($ulogin->validate()) {
                $uloginUser = new UloginUser();
                $uloginUser->setAttributes($ulogin->getAttributes());

                if ($uloginUser->save()) {
                    $questionId = Yii::app()->user->getState('question_id');
                    $question = Question::model()->findByPk($questionId);

                    /** @var Question $question */
                    if (
                        $question->createAuthor($uloginUser) &&
                        $question->save() &&
                        $question->status == Question::STATUS_CHECK
                    ) {
                        $this->redirect(['question/view', 'id' => $question->id]);
                    }
                }

            } else {

                $this->render('error');
            }
        } else {

            $this->redirect(Yii::app()->homeUrl, true);
        }
    }
}