<?php

use App\helpers\UTMHelper;

class NewsletterCommand extends CConsoleCommand
{
    // почтовая рассылка новых постов блога
    public function actionIndex()
    {
        // массив с данными всех пользователей, подписанных на рассылку
        $users = Yii::app()->db->createCommand()
                ->select('id, name, email')
                ->from('{{user}}')
                ->where('active=1 AND isSubscribed=1 AND email!="" AND role=' . User::ROLE_ROOT)
                ->limit(2)
                ->queryAll();

        //print_r($users);

        $postsCriteria = new CDbCriteria();
        $postsCriteria->addCondition('datePublication>NOW()-INTERVAL 24 HOUR');
        $postsCriteria->order = 'id DESC';

        $posts = Post::model()->findAll($postsCriteria);

        if (!sizeof($posts)) {
            // если за последние сутки не было опубликовано ни одного поста, выходим
            echo 'No fresh publications found';
            Yii::app()->end();
        }

        foreach ($users as $user) {
            $mailer = new GTMail();

            if ($posts[0] && $posts[0]->title) {
                $mailer->subject = CHtml::encode(trim($user['name']) . ', ' . $posts[0]->title);
            } else {
                $mailer->subject = CHtml::encode(trim($user['name'])) . ', помощь юристов и адвокатов';
            }

            $mailer->message = '<div style="max-width:700px; margin:0 auto;">'
                    . '<div style="border-bottom:#ccc 1px solid; padding:10px 0; background-color:#ddd;">'
                    . '<div style="text-align:center;"><img src="https://100yuristov.com/pics/2015/logo.png" /><br />ЮРИДИЧЕСКИЕ КОНСУЛЬТАЦИИ ОНЛАЙН'
                    . '<div style="font-size:20px;">8-800-500-61-85</div>'
                    . '</div></div>';
            $mailer->email = $user['email'];

            //print_r($mailer);
            //$mailer->sendMail();
            //mail($mailer->email, $mailer->subject, $mailer->message);
            //continue;

            $mailer->message .= '<table>';

            foreach ($posts as $post) {
                $mailer->message .= '<tr><td>';

                if ($post->photo) {
                    $mailer->message .= "<a href='" . Yii::app()->createUrl('post/view', ['id' => $post->id]) . "'><img src='" . Yii::app()->urlManager->getBaseUrl() . $post->getPhotoUrl('thumb') . "' /></a></div>";
                }

                $mailer->message .= "</td><td style='padding-left:10px;'><h2>" . CHtml::link(CHtml::encode($post->title), Yii::app()->createUrl('post/view', ['id' => $post->id])) .
                        '</h2><p>' .
                        $post->preview .
                        CHtml::link('Читать на сайте', Yii::app()->createUrl('post/view', ['id' => $post->id]))
                        . '</p></td></tr>';
            }

            $mailer->message .= '</table>';

            $mailer->message .= "<div style='margin-top:30px; font-size:0.8em;'>
<p>                
Вы получили это письмо, так как подписались на рассылку правовых инструкций на www.100yuristov.com. 
                Чтобы отписаться от рассылки, нажмите на <a href='" . Yii::app()->createUrl('user/unsubscribe', ['email' => $mailer->email, 'code' => md5(User::UNSUBSCRIBE_SALT . $mailer->email)]) . "'>ссылку</a>.
Это письмо отправлено роботом. Пожалуйста, не отвечайте на него.</p>
<p style='text-align:right;'>С уважением, <a href='" . Yii::app()->createUrl('/') . "'>100 юристов</a></p>
</div></div>";

            // подставляем utm метки в ссылки
            $tags = [
                'utm_medium' => 'email',
                'utm_source' => 'sendmail',
                'utm_campaign' => date('Y-m-d'),
            ];
            $mailer->message = UTMHelper::insertTags($mailer->message, $tags);

            print_r($mailer);
            //continue;

            if (true === $mailer->sendMail(true)) {
                echo 'Письмо успешно отправлено на адрес ' . $mailer->email;
            } else {
                echo 'Ошибка: не удалось отправить письмо на адрес ' . $mailer->email;
            }
        }
    }
}
