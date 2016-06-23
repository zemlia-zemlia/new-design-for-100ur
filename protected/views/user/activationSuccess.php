<?php
$this->pageTitle=Yii::app()->name . ' - Аккаунт успешно активирован';
?>

<div class="panel panel-default">
    <div class="panel-body">
        <h1>Ура, Ваш аккаунт успешно активирован!</h1>
        <p>Теперь Вы сможете пользоваться всеми возможностями сайта, которые доступны для зарегистрированных пользователей.</p>

        <?php
            echo CHtml::link("Войти на сайт", Yii::app()->createUrl('site/login'), array('class'=>'btn btn-primary'));
        ?>
    </div>
</div>
