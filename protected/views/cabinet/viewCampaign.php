<?php
/* @var $this ContactController */
/* @var $model Contact */

$this->setPageTitle("Кампания #" . $model->id . '. '. Yii::app()->name);

$this->breadcrumbs=array(
    'Кабинет'   =>  array('/cabinet'),
        'Кампания',
);

$this->widget('zii.widgets.CBreadcrumbs', array(
    'homeLink'=>CHtml::link('100 юристов', "/"),
    'separator'=>' / ',
    'links'=>$this->breadcrumbs,
 ));

?>

<h1>Кампания #<?php echo $model->id; ?></h1>

<div class='flat-panel inside'>

    <p class="text-danger">
        Для изменения параметров кампании, ее включения/выключения необходимо обратиться в техподдержку.
    </p>

        <table class="table table-bordered">
            <tr>
                <td>
                    Активность
                </td>
                <td>
                    <?php echo $model->getActiveStatusName();?>
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
            
            
            <?php if ($model->active != Campaign::ACTIVE_MODERATION):?>
            <tr>
                <td>
                    Цена лида
                </td>
                <td>
                    <?php echo MoneyFormat::rubles($model->price);?> руб.
                </td>
            </tr>
            <?php endif;?>
            
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

</div>
<br/>
<div class='flat-panel inside'>
        <?php if ($transactionsDataProvider->totalItemCount):?>
        <h2>Транзакции</h2>

        <table class="table table-bordered">
            <tr>
                <th>Время</th>
				<th>Регион</th>
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


</div>