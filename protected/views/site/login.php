<?php$this->pageTitle=Yii::app()->name . ' - Вход';$this->breadcrumbs=array(	'Вход',);?><h1>Вход</h1><div class="panel panel-gray">    <div class="panel-body">        <div class="login-form">        <?php            $this->renderPartial("_loginForm", array('model'=>$model));        ?>        </div><!-- form -->    </div></div>