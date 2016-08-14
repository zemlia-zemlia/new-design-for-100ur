<?php
$this->pageTitle=Yii::app()->name . ' - Аккаунт успешно активирован';
?>

<div class="panel panel-default">
    <div class="panel-body">
        <h1 class="vert-margin30">Ура, Ваш аккаунт успешно активирован!</h1>
        <div class="vert-margin30">
        <?php if($user->role == User::ROLE_JURIST):?>
            <p>
                Теперь вам доступна возможность отвечать на вопросы пользователей.
                <br /><br />
                Для этого войдите в личный кабинет. Параметры для доступа 
                отправлены на Вашу почту.
            </p>
        <?php else:?>
            <p>
            Заданный Вами вопрос опубликован. Ждите уведомления об ответах юристов.
                <br /><br />
                Ответы юристов Вы всегда можете посмотреть в личном кабинете. Параметры для доступа 
                отправлены на Вашу почту.
            </p>
        <?php endif;?>
        </div>    
            <?php $this->renderPartial('application.views.site._loginForm', array(
                'model'=>$loginModel, 
                'hideForgetPassword'=>true,
                ));?>
    </div>
</div>
