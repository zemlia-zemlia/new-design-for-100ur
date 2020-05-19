<?php
/**
 * Отправка письма пользователям, получившим ответ на свой вопрос несколько дней назад.
 */
class SendAfterAnswerCommand extends CConsoleCommand
{
    public $interval = 5; // интервал в днях, через который отправляем письмо после ответа
    public $days = 1; // за сколько дней брать ответы

    public function actionIndex()
    {

        // найдем информацию об ответах, вопросах, пользователях и юристах

        $answersRows = Yii::app()->db->createCommand()
                ->select('a.datetime, q.id, q.title, u.email, u.autologin, q.authorName authorName, y.name yuristName, y.lastName yuristLastName')
                ->from('{{answer}} a')
                ->leftJoin('{{question}} q', 'q.id = a.questionId')
                ->leftJoin('{{user}} u', 'u.id = q.authorId')
                ->leftJoin('{{user}} y', 'y.id = a.authorId')
                ->where('a.datetime > NOW()-INTERVAL :interval2 DAY AND a.datetime < NOW()-INTERVAL :interval1 DAY AND u.email IS NOT NULL', [':interval2' => $this->interval + $this->days, ':interval1' => $this->interval])
                ->group('q.id')
                ->order('a.id DESC')
                ->queryAll();

        echo 'Sending mails to ' . sizeof($answersRows) . ' recipients' . PHP_EOL;

        foreach ($answersRows as $row) {
            // в письмо вставляем ссылку на вопрос + метки для отслеживания переходов
            $questionLink = 'https://100yuristov.com/q/' . $row['id'] . '/?utm_source=100yuristov&utm_medium=mail&utm_campaign=answer_followup&utm_term=' . $row['id'];

            /*  проверим, есть ли у пользователя заполненное поле autologin, если нет,
             *  генерируем код для автоматического логина при переходе из письма
             * если есть, вставляем существующее значение
             * это сделано, чтобы не создавать новую строку autologin при наличии старой
             * и дать возможность залогиниться из любого письма, содержащего актуальную строку autologin
             */
            $autologinString = $row['autologin'];
            if ($autologinString) {
                $questionLink .= '&autologin=' . $autologinString;
            }

            $mailer = new GTMail();
            $mailer->subject = CHtml::encode($row['authorName']) . ', оцените ответ юриста на Ваш вопрос!';
            $path = Yii::getPathOfAlias('application.views.mail').'/sendAfterAnswer.php';
            $mailer->message = $this->renderFile($path, ['row' => $row, 'questionLink' => $questionLink]);

            // отправляем письмо на почту пользователя
            $mailer->email = $row['email'];

            if ($mailer->sendMail(true)) {
                Yii::log('Отправлено письмо пользователю ' . $row['email'] . ' с уведомлением об ответе на вопрос ' . $row['id'], 'info', 'system.web.User');
            } else {
                // не удалось отправить письмо
                Yii::log('Не удалось отправить письмо пользователю ' . $row['email'] . ' с уведомлением об ответе на вопрос ' . $row['id'], 'error', 'system.web.User');
            }
        }
    }
}
