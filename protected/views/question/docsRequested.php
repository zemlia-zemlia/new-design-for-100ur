<?php
if(Yii::app()->user->isGuest) {
    $title = "Осталось подтвердить Email";
} else {
    $title = "Заказ документа отправлен";
}


$this->setPageTitle($title . '. ' . Yii::app()->name);
?>
<div class='panel panel-default'>
    <div class='panel-body'>
        <h1><?php echo $title;?></h1>
        <?php if(Yii::app()->user->isGuest):?>
            <p>
                Для подтверждения своей почты перейдите по ссылке, которую мы отправили Вам в письме.
                После этого Ваш заказ будет отправлен юристам.
            </p>
        <?php else:?>
            <p>
                Ваш заказ принят. Вы получите уведомление, когда юрист примет его в работу.
            </p>
            <p>
                Все Ваши заказы доступны в личном кабинете.
            </p>
            <p class="center-align">
                <?php echo CHtml::link('Перейти в личный кабинет', Yii::app()->createUrl('/user/'), ['class' => 'yellow-button']);?>
            </p>
        <?php endif;?>
        
    </div>
</div>