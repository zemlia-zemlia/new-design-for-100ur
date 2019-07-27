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

<h1 class="vert-margin20">Ваш баланс: <?php echo MoneyFormat::rubles(Yii::app()->user->balance); ?> руб.</h1>

<p>
    <?php if (Yii::app()->user->campaignsModeratedCount > 0): ?>
    <h3 class="text-uppercase">Пополнение баланса</h3>
    
    <div class="row">
        <div class="col-sm-6 text-center">
            <h3>Вариант 1</h3>
					 Карта Сбербанка. Номер: 5469 3800 2197 4653 <br/> 
					 получатель: Виталий Николаевич Т.<br />
					 <p class="small">
                    <strong>(комиссия 0% для карт Московского региона)<br/>
                    для карт других регионов от 1% <br/> (у каждого банка индивидуально)<br/>
                    зачисление на баланс в течении 30 минут</strong></p>


            <div class="alert alert-danger">
                <p>
                    <strong>ВНИМАНИЕ!</strong><br/> При оплате на карту в сообщении к платежу укажите <strong>"Пользователь ID <?php echo Yii::app()->user->id; ?>"</strong>
                </p>
            </div>
        </div>
        <div class="col-sm-6">
            <h3>Вариант 2</h3>
            <p class="text-center">Оплата онлайн <br/>
    			зачисление - мгновенно</p>
            <?php echo $this->renderPartial('application.views.transaction._yandexForm'); ?>
        </div>
    </div>

<div class="row">
	<div class="col-sm-10 col-sm-offset-1">
            <h3>Вариант 3</h3>
            <p class="text-center">Безналичная оплата <br/>
    			Для юридических лиц и ИП, с заключением договора и получением закрывающих документов по бухгалтерии. Мы с вами подписываем договор и вы оплачивание на расчетный счет ИП. Для этого необходимо, отправить ваши реквизиты нам на почту admin@100yuristov.com, мы подготовим договор и после его подписания можно будет производить оплату.</p>
        </div>

</div>


<?php else: ?>
    <span class="text-warning">для пополнения счета у Вас должна быть хотя бы одна активная кампания, одобренная модератором</span>
<?php endif; ?>
</p>

	<h2>История изменения баланса</h2>
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
