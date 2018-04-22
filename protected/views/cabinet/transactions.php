<?php
$this->setPageTitle("Мои транзакции" . Yii::app()->name);

$this->breadcrumbs = array(
    'Кабинет' => array('/cabinet'),
    'Мои транзакции',
);

$this->widget('zii.widgets.CBreadcrumbs', array(
    'homeLink' => CHtml::link('100 юристов', "/"),
    'separator' => ' / ',
    'links' => $this->breadcrumbs,
));
?>

<h1 class="vert-margin20">Баланс: <?php echo Yii::app()->user->balance; ?> руб.</h1>

<p>
    <?php if (Yii::app()->user->campaignsModeratedCount > 0): ?>
    <h3 class="text-uppercase">Пополнение баланса</h3>
    
    <div class="row">
        <div class="col-sm-6">
            <h3>Вариант 1</h3>
            <ul>
                <li> Карта Сбербанка. Номер: 4276 3800 1972 5212, получатель Виталий Николаевич Т.<br />
                    (комиссия 0% для карт Московского региона)
                </li>
                <li>На рассчетный счет организации (с заключением договора, платеж от 10 000 руб.)</li>
            </ul>

            <div class="alert alert-danger">
                <p>
                    <strong>ВНИМАНИЕ!</strong><br/> При оплате на карту в сообщении к платежу укажите <strong>"Пополнение баланса пользователя <?php echo Yii::app()->user->id; ?>"</strong>
                </p>
            </div>
        </div>
        <div class="col-sm-6">
            <h3>Вариант 2</h3>
            <p class="text-center">Оплата онлайн</p>
            <?php echo $this->renderPartial('application.views.transaction._yandexForm'); ?>
        </div>
    </div>

<?php else: ?>
    <span class="text-warning">для пополнения счета у Вас должна быть хотя бы одна активная кампания, одобренная модератором</span>
<?php endif; ?>
</p>

<h2>История изменения баланса</h2>

<table class="table table-bordered">
    <tr>
        <th>Время</th>
        <th>Кампания</th>
        <th>Сумма</th>
        <th>Описание</th>
    </tr>

    <?php
    $this->widget('zii.widgets.CListView', array(
        'dataProvider' => $transactionsDataProvider,
        'itemView' => 'application.views.transactionCampaign._view',
        'emptyText' => 'Не найдено ни одной транзакции',
        'summaryText' => 'Показаны транзакции с {start} до {end}, всего {count}',
        'pager' => array('class' => 'GTLinkPager') //we use own pager with russian words
    ));
    ?>
</table>

