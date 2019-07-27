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

<style>
    .table>thead>tr>th, .table>tbody>tr>th, .table>tfoot>tr>th, .table>thead>tr>td, .table>tbody>tr>td, .table>tfoot>tr>td {
        padding:2px;
    }
</style>

<h1>Кампания #<?php echo $model->id; ?></h1>

<div class="row vert-margin30">
<div class="col-sm-12">
	<table class="table table-bordered">
    <tr>
        <td>
            Тип кампании
        </td>
        <td>
            <?php echo $model->getTypeName();?>
        </td>
    </tr>
    <tr>
        <td>
            Покупатель
        </td>
        <td>
            <?php echo CHtml::link(CHtml::encode($model->buyer->lastName . ' ' . $model->buyer->name), Yii::app()->createUrl('/admin/user/view', array('id' => $model->buyer->id)));?>
            <?php echo CHtml::encode($model->buyer->email);?>
        </td>
    </tr>
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
            Отправлять лиды на Email
        </td>
        <td>
            <?php echo $model->sendEmail?'Да':'Нет';?>
        </td>
    </tr>
    <tr>
        <td>
            Отправлять лиды по API
        </td>
        <td>
            <?php echo $model->sendToApi?'Да':'Нет';?>
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
            Дни
        </td>
        <td>
            <?php 
                $workDays = array();
                $workDays = explode(',', $model->days);
            ?>
            <?php for($dayNumber=1; $dayNumber<=7; $dayNumber++):?>
                <?php 
                    if(!in_array($dayNumber, $workDays)) {
                        $labelClass = 'label-default';
                    } else {
                        $labelClass = ($dayNumber>5)?'label-danger':'label-success';
                    }
                ?>
                <span class="label <?php echo $labelClass;?>">
                    <?php  echo CustomFuncs::getWeekDays()[$dayNumber];?>
                </span> &nbsp;
            <?php endfor;?>
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
            <?php echo MoneyFormat::rubles($model->price);?>
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
            <?php echo $model->calculateCurrentBrakPercent(date('Y-m-d', time()-86400));?>
            /
            <?php echo $model->calculateCurrentBrakPercent();?>
            /
            <?php echo $model->brakPercent;?>
        </td>
    </tr>
</table>

<?php echo CHtml::link('Редактировать', Yii::app()->createUrl('/admin/campaign/update', array('id'=>$model->id)), array('class'=>'btn btn-primary'));?>
</div>
</div>


<div class="row">


<div class="col-sm-8">
	<h2>Лиды</h2>
<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'  =>  $leadsDataProvider,
	'itemView'      =>  'application.modules.admin.views.lead._view',
        'template'      =>  '{summary}{pager}{items}{pager}',
        'emptyText'     =>  'Не найдено ни одного лида',
        'summaryText'   =>  'Показаны лиды с {start} до {end}, всего {count}',
        'pager'         =>  array('class'=>'GTLinkPager') //we use own pager with russian words
)); ?>

</div>

<div class="col-sm-4">
	<h2>Статистика продаж лидов по дням</h2>
    <div class="vert-margin30">
       <?php $this->renderPartial('application.modules.admin.views.lead._searchFormDates', array(
           'model'=>$searchModel,
           'action' =>  Yii::app()->createUrl('admin/campaign/view', array('id' => $model->id)),
           ));?> 
    </div>
    <?php if(is_array($leadsStats) && is_array($leadsStats['dates'])):?>

        <table class="table table-bordered">
            <tr>
                <th>Дата</th>
                <th class="text-right">Количество</th>
                <th class="text-right">Сумма</th>
            </tr>
            <?php foreach ($leadsStats['dates'] as $date=>$leadsByDate):?>
            <tr>
                <td><?php echo CustomFuncs::niceDate($date, false, false);?></td>
                <td class="text-right"><?php echo $leadsByDate['count'];?></td>
                <td class="text-right"><?php echo MoneyFormat::rubles($leadsByDate['sum']);?> руб.</td>
            </tr>
            <?php endforeach;?>
            <tr>
                <th></th>
                <th class="text-right"><?php echo $leadsStats['total'];?></th>
                <th class="text-right"><?php echo MoneyFormat::rubles($leadsStats['sum']);?> руб.</th>
            </tr>
        </table>
    <?php endif;?>
</div>





    </div>