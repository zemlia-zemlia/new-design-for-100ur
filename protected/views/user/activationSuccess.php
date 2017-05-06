<?php
$this->pageTitle=Yii::app()->name . ' - Аккаунт успешно активирован';
?>

<div class="panel panel-default">
    <div class="panel-body">
        <h1 class="vert-margin30">Ура, Ваш аккаунт успешно активирован!</h1>
        <div class="vert-margin30">
        <?php if($user->role == User::ROLE_JURIST):?>
            <p>
                Теперь Вам необходимо заполнить свой профиль.
            </p>
            
            <?php echo CHtml::link("Заполнить профиль", Yii::app()->createUrl('user/update', array('id' => Yii::app()->user->id)), array('class' => 'btn btn-primary'));?>
        <?php else:?>
            <p class="vert-margin30">
            Заданный Вами вопрос опубликован. Ждите уведомления об ответах юристов.
                <br /><br />
                Ответы юристов Вы всегда можете посмотреть в личном кабинете. Параметры для доступа 
                отправлены на Вашу почту.
            </p>
            
            <?php 
                //CustomFuncs::printr($question);
            ?>
            
            <?php if($question->price):?>
                <div class="center-align">
                    <h3>Оплатите вопрос и получите быстрый гарантированный ответ юриста</h3>
                    <?php $this->renderPartial('application.views.question._paymentForm', array('question'=>$question));?>
                </div>
            <?php endif;?>
            
        <?php endif;?>
        </div>    
            
    </div>
</div>
