<?php
$this->pageTitle = "Транзакции. " . Yii::app()->name;
?>


<?php if ($justCreated == true): ?>
    <div class="alert alert-success text-center">
        <h4>Заявка на вывод средств создана</h4>
        <p>Заявка создана и отправлена на рассмотрение модератору</p>
    </div>
<?php endif; ?>
<div class="box">
    <div class="box-header">
        <div class="box-title">Информация о балансе</div>
    </div>
    <div class="box-body">
        <table class="table">
            <tr>
                <td class="center-align">
                    Ваш баланс:<br/> <strong><?php echo MoneyFormat::rubles($balance); ?> руб.</strong><br/> (из них
                    холд <?php echo MoneyFormat::rubles($hold); ?> руб.)
                </td>
                <td class="center-align">
                    Доступно для вывода:<br/> <strong>
                        <?php if (($balance - $hold) < PartnerTransaction::MIN_WITHDRAW): ?>
                            <small><span
                                        class="text-danger">Минимальная сумма для вывода - 1000&nbsp;руб.</span></small>
                        <?php else: ?>
                        <?php echo MoneyFormat::rubles($balance - $hold); ?> руб.</strong>
                    <?php endif; ?>
                </td>
                <td>
                    <?php echo $this->renderPartial('_form', array(
                        'model' => $transaction,
                    )); ?>
                    <div class="text-center">
                        <small><strong>Заявки на вывод средств обрабатываются в течении 3 (трёх) рабочих дней.</strong></small>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</div>
<hr/>

<?php if ($requestsDataProvider->totalItemCount): ?>
    <div class="box">
        <div class="box-header">
            <div class="box-title">Активные заявки на вывод средств</div>
        </div>
        <div class="box-body">
            <table class="table table-bordered table-hover table-striped">
                <thead>
                <tr>
                    <th>Дата</th>
                    <th>Сумма</th>
                    <th>Комментарий</th>
                    <th>Статус</th>
                </tr>
                </thead>
                <?php $this->widget('zii.widgets.CListView', array(
                    'dataProvider' => $requestsDataProvider,
                    'itemView' => '_viewRequest',
                    'emptyText' => 'Не найдено ни одной заявки',
                    'summaryText' => 'Показаны заявки с {start} до {end}, всего {count}',
                    'pager' => array('class' => 'GTLinkPager') //we use own pager with russian words
                )); ?>
            </table>
        </div>
    </div>
<?php endif; ?>

<div class="box">
    <div class="box-header">
        <div class="box-title">Мои транзакции</div>
    </div>
    <div class="box-body">
        <table class="table table-bordered table-hover table-striped">
            <thead>
            <tr>
                <!--<th>ID</th> -->
                <th>Дата</th>
                <th>Сумма</th>
                <th>Комментарий</th>
            </tr>
            </thead>
            <?php $this->widget('zii.widgets.CListView', array(
                'dataProvider' => $dataProvider,
                'itemView' => '_view',
                'emptyText' => 'Не найдено ни одной транзакции',
                'summaryText' => 'Показаны транзакции с {start} до {end}, всего {count}',
                'pager' => array('class' => 'GTLinkPager') //we use own pager with russian words
            )); ?>
        </table>
    </div>
</div>
