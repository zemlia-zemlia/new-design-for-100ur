<?php
/*
 * блок с подписью письма и социальными ссылками
 */
?>
<div id="footer" style="margin-top:30px;
        border-top:#dde3e8 2px solid;
        padding:10px 0;">

        <p style="text-align: center;">С уважением, юридический портал <a href='https://100yuristov.com/?utm_medium=email&utm_source=message_footer'>100&nbsp;Юристов</a></p>
     
    
    <p style="text-align: center;">
        Это письмо попало к Вам случайно? <br />
    <?php echo CHtml::link('Отписаться от писем', Yii::app()->createUrl('user/unsubscribe', ['email' => $mailer->email, 'code' => md5(User::UNSUBSCRIBE_SALT . $mailer->email)]), ['style' => ' padding: 2px;
            text-align: center;
            font-size: 12px;
            font-family: Arial, sans-serif;
            font-weight: bold;
            border-radius: 4px;
            line-height: 17px;
            margin:0 auto;
        ']); ?>
    </p>
</div>

