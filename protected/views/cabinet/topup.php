<?php

$this->setPageTitle('Пополнение баланса.' . Yii::app()->name);

$this->breadcrumbs = [
    'Кабинет' => ['/cabinet'],
        'Пополнение баланса',
];

$this->widget('zii.widgets.CBreadcrumbs', [
    'homeLink' => CHtml::link('100 юристов', '/'),
    'separator' => ' / ',
    'links' => $this->breadcrumbs,
 ]);

?>

<h1 class="vert-margin20">Пополнение баланса</h1>

<p>
    Доступные на данный момент способы пополнения баланса:
</p>

<ul>
    <li>Карта Сбербанка. Номер: 5469 3800 2197 4653, получатель Виталий Николаевич Т.</li>
    <li>Яндекс Деньги. Номер кошелька: 410012948838662</li>
    <li>На рассчетный счет организации (с заключением договора, платеж от 15 000 руб.)</li>
</ul>

<div class="alert alert-danger">
    <p>
    <strong>ВНИМАНИЕ!</strong><br/> При оплате на карту или Яндекс Деньги в сообщении к платежу укажите <strong>"Пополнение баланса пользователя <?php echo Yii::app()->user->id; ?>"</strong>
    </p>
</div>
