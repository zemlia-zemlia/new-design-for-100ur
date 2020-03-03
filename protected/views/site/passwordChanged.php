<?php
$this->setPageTitle('Пароль изменен.' . Yii::app()->name);
?>

<h1>Пароль успешно изменен</h1>
<p class="text-center">
    <?php echo CHtml::link('Войти в личный кабинет', Yii::app()->createUrl('site/login')); ?>
</p>

<?php if ($isYurcrmRegistered):?>
<h2>Хотите бонус?</h2>
<p class="text-center">
    Мы также бесплатно создали для вас аккаунт в нашей CRM для юридических компаний.<br />
    Адрес: <a href="https://www.yurcrm.ru">www.yurcrm.ru</a>, для входа используйте свою почту и пароль от
    100 Юристов.
</p>
<?php endif; ?>