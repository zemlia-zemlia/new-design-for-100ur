<?php$this->pageTitle=Yii::app()->name . ' - Вход';$this->breadcrumbs=array(	'Вход',);?><div class="flat-panel inside"><h1>Авторизация</h1>        <div class="login-form">        <?php            $this->renderPartial("_loginForm", array('model'=>$model));        ?>        </div><!-- form --></div>