<?php
    $this->pageTitle = "Транзакции. " . Yii::app()->name;
?>

<div  class="vert-margin30">
<h1>Кабинет вебмастера</h1>
</div>

<?php if($justCreated == true):?>
<div class="alert alert-success text-center">
    <h4>Заявка на вывод средств создана</h4>
    <p>Заявка создана и отправлена на рассмотрение модератору</p>
</div>
<?php endif;?>

<table class="table">
    <tr>
        <td class="center-align">
            Ваш баланс:<br /> <strong><?php echo $balance;?> руб.</strong><br /> (из них холд <?php echo $hold;?> руб.)
        </td>
        <td class="center-align">
            Доступно для вывода:<br /> <strong><?php echo $balance - $hold;?> руб.</strong>
        </td>
        <td>
            <?php echo $this->renderPartial('_form', array(
                'model' => $transaction,
            ));?>
        </td>
        
    </tr>
</table>


<?php if($requestsDataProvider->totalItemCount):?>
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
    <?php $this->widget('zii.widgets.CListView', array(
            'dataProvider'=>$requestsDataProvider,
            'itemView'=>'_viewRequest',
            'emptyText' =>  'Не найдено ни одной заявки',
            'summaryText'=>'Показаны заявки с {start} до {end}, всего {count}',
            'pager'=>array('class'=>'GTLinkPager') //we use own pager with russian words
    )); ?>
    </table>
<?php endif;?>

<h2>Мои транзакции</h2>
<table class="table table-bordered table-hover table-striped">
    <thead>
    <tr>
        <th>ID</th>
        <th>Дата</th>
        <th>Сумма</th>
        <th>Комментарий</th>
    </tr>
    </thead>
<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
        'emptyText' =>  'Не найдено ни одной транзакции',
        'summaryText'=>'Показаны транзакции с {start} до {end}, всего {count}',
        'pager'=>array('class'=>'GTLinkPager') //we use own pager with russian words
)); ?>
</table>
