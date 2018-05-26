<?php
$this->setPageTitle("Пароль изменен." . Yii::app()->name);
?>

<h1>Пароль успешно изменен</h1>
<p class="text-center">
    <?php echo CHtml::link('Войти в личный кабинет', Yii::app()->createUrl('site/login'));?>
</p>