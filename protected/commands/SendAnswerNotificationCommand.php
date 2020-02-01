<?php
class SendAnswerNotificationCommand extends CConsoleCommand
{
    // рассылка уведомлений пользователям об ответах на их вопросы
    public function actionIndex()
    {
        // найдем ответы, оставленные с начала декабря 2016 с вопросами и авторами вопросов
        
        $emailsSent = array(); // массив с адресами, на которые уже отправили письма
        
        $criteria = new CDbCriteria;
        $criteria->addCondition("DATE(t.`datetime`)>NOW()-INTERVAL 40 DAY");
        
        $answers = Answer::model()->with(array('question', 'question.author'))->findAll($criteria);
                
        foreach ($answers as $answer) {
            //echo $answer->id . ': ' . $answer->authorId . ': ' . $answer->datetime . ':' . $answer->question->id . ':' . $answer->question->author->id . PHP_EOL;
            
            $question = $answer->question;
            $user = $answer->question->author;
            
            if (!($user instanceof User) || !($question instanceof Question) || !$user->email) {
                continue;
            }
            
            // если уже отправили письмо на этот адрес, не отправляем
            if (in_array($user->email, $emailsSent)) {
                continue;
            }
            
            if ($user->sendAnswerNotification($question, $answer) === true) {
                $emailsSent[] = $user->email;
            }
        }
    }
}
