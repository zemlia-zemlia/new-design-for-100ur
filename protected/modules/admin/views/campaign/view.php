<?php
/* @var $this CampaignController */
/* @var $model Campaign */

$this->breadcrumbs=array(
	'Кампании'=>array('index'),
	$model->id,
);

?>

<?php

$this->widget('zii.widgets.CBreadcrumbs', array(
    'homeLink'=>CHtml::link('100 юристов',"/admin"),
    'separator'=>' / ',
    'links'=>$this->breadcrumbs,
 ));

?>

<h1>Кампания #<?php echo $model->id; ?></h1>

<table class="table table-bordered">
    <tr>
        <td>
            Покупатель
        </td>
        <td>
            <?php echo $model->buyer->lastName . ' ' . $model->buyer->name;?>
        </td>
    </tr>
    <tr>
        <td>
            Активность
        </td>
        <td>
            <?php echo $model->active?'Да':'Нет';?>
        </td>
    </tr>
    <tr>
        <td>
            Отправлять лиды на Email
        </td>
        <td>
            <?php echo $model->sendEmail?'Да':'Нет';?>
        </td>
    </tr>
    <tr>
        <td>
            Регион
        </td>
        <td>
            <?php echo $model->region->name;?> 
            <?php echo $model->town->name;?>
        </td>
    </tr>
    <tr>
        <td>
            Время
        </td>
        <td>
            С <?php echo $model->timeFrom;?> до
            <?php echo $model->timeTo;?>
        </td>
    </tr>
    <tr>
        <td>
            Цена лида
        </td>
        <td>
            <?php echo $model->price;?>
        </td>
    </tr>
    <tr>
        <td>
            Баланс
        </td>
        <td>
            <?php echo $model->balance;?>
        </td>
    </tr>
    <tr>
        <td>
            Лимит заявок в день
        </td>
        <td>
            <?php echo $model->leadsDayLimit;?>
        </td>
    </tr>
    <tr>
        <td>
            Процент брака
        </td>
        <td>
            <?php echo $model->brakPercent;?>
        </td>
    </tr>
</table>

<?php echo CHtml::link('Редактировать', Yii::app()->createUrl('/admin/campaign/update', array('id'=>$model->id)), array('class'=>'btn btn-primary'));?>


<?php if($transactionsDataProvider->totalItemCount):?>
<h2>Транзакции</h2>

<table class="table table-bordered">
    <tr>
        <th>Время</th>
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

<?php endif;?>