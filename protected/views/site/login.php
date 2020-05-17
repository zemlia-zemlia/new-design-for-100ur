<?php

$this->pageTitle = Yii::app()->name . ' - Вход';

$this->breadcrumbs = [
    'Вход'];

?>
<div class="flat-panel inside">    <h1>Авторизация</h1>    <div class="login-form">        <?php
        $this->renderPartial('_loginForm', ['model' => $model]);
        ?>
    </div><!-- form -->    <h2>Или через социальную сеть</h2>    <?php $this->renderPartial('application.views.question._formSocials'); ?>
</div>