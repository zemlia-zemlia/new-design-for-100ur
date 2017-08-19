<?php
$this->setPageTitle("Мои транзакции" . Yii::app()->name);

$this->breadcrumbs=array(
	'Кабинет'   =>  array('/cabinet'),
        'Мои транзакции',
);

$this->widget('zii.widgets.CBreadcrumbs', array(
    'homeLink'=>CHtml::link('100 юристов',"/"),
    'separator'=>' / ',
    'links'=>$this->breadcrumbs,
 ));

?>

<h1 class="vert-margin20">Мой баланс</h1>

<p>
    Ваш баланс: <?php echo Yii::app()->user->balance;?> руб.
    
    <?php if(Yii::app()->user->campaignsModeratedCount > 0):?>
            <?php echo CHtml::link('Пополнить баланс', Yii::app()->createUrl('/cabinet/topup'));?>
    <?php else:?>
            <span class="text-warning">для пополнения счета у Вас должна быть хотя бы одна активная кампания, одобренная модератором</span>
    <?php endif;?>
</p>
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
    <strong>ВНИМАНИЕ!</strong><br/> При оплате на карту или Яндекс Деньги в сообщении к платежу укажите <strong>"Пополнение баланса пользователя <?php echo Yii::app()->user->id;?>"</strong>
    </p>
</div>
        <h1>Транзакции</h1>

        <table class="table table-bordered">
            <tr>
                <th>Время</th>
                <th>Кампания</th>
                <th>Сумма</th>
                <th>Описание</th>
            </tr>

        <?php $this->widget('zii.widgets.CListView', array(
                'dataProvider'  =>  $transactionsDataProvider,
                'itemView'      =>  'application.views.transactionCampaign._view',
                'emptyText'     =>  'Не найдено ни одной транзакции',
                'summaryText'   =>  'Показаны транзакции с {start} до {end}, всего {count}',
                'pager'         =>  array('class'=>'GTLinkPager') //we use own pager with russian words
        )); ?>
        </table>

