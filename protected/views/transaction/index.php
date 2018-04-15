<?php
$this->pageTitle = "Транзакции пользователя. " . Yii::app()->name;

Yii::app()->clientScript->registerScriptFile('/js/balance.js');
?>

<div  class="vert-margin30">
    <h1>Мои финансы</h1>
</div>

<div class="vert-margin30 text-center">
    <p class="lead">Ваш баланс: <strong><?php echo round((float)Yii::app()->user->getBalance(true), 2); ?> руб.</strong>
        <a data-toggle="collapse" href="#collapse-add-balance" aria-expanded="false" aria-controls="collapse-add-balance">пополнить</a>
    </p>
</div>

<?php if ($justCreated == true): ?>
    <div class="alert alert-success text-center">
        <h4>Заявка на вывод средств создана</h4>
        <p>Заявка создана и отправлена на рассмотрение модератору</p>
    </div>
<?php endif; ?>

<div class="collapse" id="collapse-add-balance">
    <h2>Пополнить баланс</h2>
    <form method="POST" action="https://money.yandex.ru/quickpay/confirm.xml" class="balance-form">    
        <input type="hidden" name="receiver" value="410012948838662">      
        <input type="hidden" name="label" value="<?php echo Yii::app()->user->id; ?>">    
        <input type="hidden" name="quickpay-form" value="shop">    
        <input type="hidden" name="successURL" value="<?php echo Yii::app()->createUrl('transaction/index');?>">    
        <input type="hidden" name="targets" value="Пополнение баланса пользователя <?php echo Yii::app()->user->id; ?>">    
        <div class="form-group">
            <div class="input-group">
                <input type="text" name="user-sum" value="500" data-type="number" class="form-control text-right">
                <div class="input-group-addon">руб.</div>
            </div>
        </div>
        <input type="hidden" name="sum" value="0" />
        <div class="radio">
            <label><input type="radio" name="paymentType" value="PC" checked>Яндекс.Деньгами <br />
                <small>Комиссия 0.5%
                </small>
            </label>    
            <label><input type="radio" name="paymentType" value="AC">Банковской картой<br />
                <small>Комиссия 2%
                </small>
            </label> 
        </div>
        
        <p>
            К оплате: <span id="sum-for-pay"></span> руб.
        </p>
        <input type="submit" class="btn btn-primary" value="Пополнить баланс">
    </form>
</div>

<?php if (Yii::app()->user->role == User::ROLE_PARTNER): ?>
    <table class="table">
        <tr>
            <td class="center-align">
                Доступно для вывода:<br /> <strong>
                    <?php if (($balance) < PartnerTransaction::MIN_WITHDRAW_REFERAL): ?>
                        <small><span class="text-danger">Минимальная сумма для вывода - <?php echo PartnerTransaction::MIN_WITHDRAW_REFERAL; ?>&nbsp;руб.</span></small>
                    <?php else: ?>
                        <?php echo $balance; ?> руб.</strong>
                <?php endif; ?>
            </td>
            <td>
                <?php
                echo $this->renderPartial('_form', array(
                    'model' => $transaction,
                ));
                ?>
            </td>

        </tr>
    </table>

    <hr/>
<?php endif; ?>

<?php if ($requestsDataProvider->totalItemCount): ?>
    <h2>Заявки на вывод средств</h2>
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
        $this->widget('zii.widgets.CListView', array(
            'dataProvider' => $requestsDataProvider,
            'itemView' => '_viewRequest',
            'emptyText' => 'Не найдено ни одной заявки',
            'summaryText' => 'Показаны заявки с {start} до {end}, всего {count}',
            'pager' => array('class' => 'GTLinkPager') //we use own pager with russian words
        ));
        ?>
    </table>
<?php endif; ?>

<h2>История изменения баланса</h2>
<table class="table table-bordered table-hover table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Дата</th>
            <th>Сумма</th>
            <th>Комментарий</th>
        </tr>
    </thead>
    <?php
    $this->widget('zii.widgets.CListView', array(
        'dataProvider' => $dataProvider,
        'itemView' => '_view',
        'emptyText' => 'Не найдено ни одной транзакции',
        'summaryText' => 'Показаны транзакции с {start} до {end}, всего {count}',
        'pager' => array('class' => 'GTLinkPager') //we use own pager with russian words
    ));
    ?>
</table>
