<?php
$this->pageTitle=Yii::app()->name . ' - Аккаунт успешно активирован';
?>
<h1>Ура, Ваш аккаунт успешно активирован!</h1>
<p>Теперь Вы сможете пользоваться всеми возможностями сайта, которые доступны для зарегистрированных пользователей.</p>

<?php if(Yii::app()->user->isGuest):?>
<div class="center-align">
    <?php
        echo CHtml::link("Войти в систему", Yii::app()->createUrl('site/login'), array('class'=>'btn btn-primary'));
    ?>
</div>
<?php endif;?>

