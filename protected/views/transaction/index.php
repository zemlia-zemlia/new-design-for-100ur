<?php
$this->pageTitle = 'Транзакции пользователя. ' . Yii::app()->name;

?>
<?php
foreach (Yii::app()->user->getFlashes() as $key => $message) {
    echo '<div class="alert alert-' . $key . '">' . $message . "</div>\n";
}
?>
<div class="vert-margin30">
    <h1>Мои финансы</h1>
</div>

<div class="vert-margin30 text-center">
    <p class="lead">Ваш баланс: <strong><?php echo MoneyFormat::rubles(Yii::app()->user->getBalance(true), 2); ?> руб.</strong>
    </p>
</div>

<div class="row">
    <div class="col-lg-6">
        <?php echo $this->renderPartial('_yandexForm'); ?>
    </div>
    <div class="col-lg-6">
        <?php
        echo $this->renderPartial('_form_jurist', [
            'model' => $transaction,
        ]);
        ?>
    </div>


</div>


<?php if (User::ROLE_PARTNER == Yii::app()->user->role): ?>
    <table class="table">
        <tr>
            <td class="center-align">
                Доступно для вывода:<br/> <strong>
                    <?php if (($balance) < PartnerTransaction::MIN_WITHDRAW_REFERAL): ?>
                        <small><span
                                    class="text-danger">Минимальная сумма для вывода - <?php echo PartnerTransaction::MIN_WITHDRAW_REFERAL; ?>&nbsp;руб.</span></small>
                    <?php else: ?>
                    <?php echo MoneyFormat::rubles($balance); ?> руб.</strong>
                <?php endif; ?>
            </td>
            <td>
                <?php
                echo $this->renderPartial('_form', [
                    'model' => $transaction,
                ]);
                ?>
            </td>

        </tr>
    </table>

<?php endif; ?>

<hr/>

<?php if ($requestsDataProvider->totalItemCount): ?>
    <h2>Активные заявки на вывод средств</h2>
    <table class="table table-bordered table-hover table-striped">
        <thead>
        <tr>
            <th>Дата</th>
            <th>Сумма</th>
            <th>Комментарий</th>
            <th>Статус</th>
        </tr>
        </thead>
        <?php

        $this->widget('zii.widgets.CListView', [
            'dataProvider' => $requestsDataProvider,
            'itemView' => '_viewRequest',
            'emptyText' => 'Не найдено ни одной заявки',
            'summaryText' => 'Показаны заявки с {start} до {end}, всего {count}',
            'pager' => ['class' => 'GTLinkPager'], //we use own pager with russian words
        ]);
        ?>
    </table>
<?php endif; ?>

<h2>История изменения баланса</h2>
<table class="table table-bordered table-hover table-striped">
    <thead>
    <tr>
        <th>Дата</th>
        <th>Сумма</th>
        <th>Комментарий</th>
    </tr>
    </thead>
    <?php
    $this->widget('zii.widgets.CListView', [
        'dataProvider' => $dataProvider,
        'itemView' => '_view',
        'emptyText' => 'Не найдено ни одной транзакции',
        'summaryText' => 'Показаны транзакции с {start} до {end}, всего {count}',
        'pager' => ['class' => 'GTLinkPager'], //we use own pager with russian words
    ]);
    ?>
</table>
