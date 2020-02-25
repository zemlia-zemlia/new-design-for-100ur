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

    <style>
        .comments-item, .table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
            padding: 1px 1px;
        }
    </style>
<div class="text-center">
    <h1>Ваш баланс: <?php echo MoneyFormat::rubles(Yii::app()->user->balance); ?> руб.</h1>
</div>

    <p>
<?php if (Yii::app()->user->campaignsModeratedCount > 0): ?>

    <h3 class="text-uppercase">Пополнение баланса:</h3>

    <div class="row">
        <div class="col-sm-6 text-center">
            <div class="box">
                <div class="box-header">
                    <div class="box-title">Вариант 1: С карты на карту</div>
                </div>
                <div class="box-body">
                    Карта Сбербанка. Номер: 5469 3800 2197 4653 <br/>
                    получатель: Виталий Николаевич Т.<br/>
                    <p class="small">
                        <strong>(комиссия 0% для карт Московского региона)<br/>
                            для карт других регионов от 1% <br/> (у каждого банка индивидуально)<br/>
                            зачисление на баланс в течении 30 минут</strong></p>
                    <div class="alert alert-warning">
                        <p>
                            <strong>ВНИМАНИЕ!</strong><br/> При оплате c карты сбербанка в сообщении к платежу укажите <strong>"Аккаунт
                                ID <?php echo Yii::app()->user->id; ?>" </strong> Если отправляете платеж с другого банка, просим скинуть чек или скрин платежа нам на почту с указанием ID вашего аккаунта.
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 text-center">
            <div class="box">
                <div class="box-header">
                    <div class="box-title">Вариант 2: Оплата онлайн</div>
                </div>
                <div class="box-body">
                    <p class="text-center">зачисление - мгновенно</p>
                    <?php echo $this->renderPartial('application.views.transaction._yandexForm'); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-10 col-sm-offset-1">
            <div class="box">
                <div class="box-header">
                    <div class="box-title">Вариант 3: Безналичная оплата</div>
                </div>
                <div class="box-body">
                    <p class="text-center">
                        Для юридических лиц и ИП, с заключением договора и получением закрывающих документов по
                        бухгалтерии. Мы
                        с вами подписываем договор и вы оплачивание на расчетный счет ИП. Для этого необходимо,
                        отправить ваши
                        реквизиты нам на почту admin@100yuristov.com, мы подготовим договор и после его подписания можно
                        будет
                        производить оплату.</p>
                </div>
            </div>
        </div>

    </div>


<?php else: ?>
    <span class="text-warning">для пополнения счета у Вас должна быть хотя бы одна активная кампания, одобренная модератором</span>
<?php endif; ?>
    </p>
<?php if (!in_array(Yii::app()->user->id, [5379])): ?>
    <div class="box">
        <div class="box-header">
            <div class="box-title">История изменения баланса</div>
        </div>
        <div class="box-body">
            <small>
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
            </small>
        </div>
    </div>
<?php endif; ?>