<?php
/* @var $this CampaignController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Кампании',
);

$this->pageTitle = "Кампании. " . Yii::app()->name;

Yii::app()->clientScript->registerScriptFile('/js/admin/campaign.js');

?>

<style>
    .table>thead>tr>th, .table>tbody>tr>th, .table>tfoot>tr>th, .table>thead>tr>td, .table>tbody>tr>td, .table>tfoot>tr>td {
        padding:0px;
    }
</style>

<h1>Кампании</h1>


<div class="vert-margin20">
    <p>
        <?php echo CHtml::link("Активные", Yii::app()->createUrl('/admin/campaign/index', array('type' => 'active')), array('class' => ($type == 'active') ? 'text-muted' : ''));?> &nbsp; 
        <?php echo CHtml::link("Пассивные", Yii::app()->createUrl('/admin/campaign/index', array('type' => 'passive')), array('class' => ($type == 'passive') ? 'text-muted' : ''));?> &nbsp; 
        <?php echo CHtml::link("Отключены", Yii::app()->createUrl('/admin/campaign/index', array('type' => 'inactive')), array('class' => ($type == 'inactive') ? 'text-muted' : ''));?> &nbsp; 
        <?php echo CHtml::link("Архив", Yii::app()->createUrl('/admin/campaign/index', array('type' => 'archive')), array('class' => ($type == 'archive') ? 'text-muted' : ''));?> &nbsp; 
        <?php echo CHtml::link("Одобренные", Yii::app()->createUrl('/admin/campaign/index', array('type' => 'accepted')), array('class' => ($type == 'accepted') ? 'text-muted' : ''));?> &nbsp; 

        <?php echo CHtml::link("На модерации", Yii::app()->createUrl('/admin/campaign/index', array('type' => 'moderation')), array('class' => ($type == 'moderation') ? 'text-muted' : ''));?>
        <span class="badge"><?php echo Campaign::getModerationCount();?></span>
        
        &nbsp; 
    </p>
</div>

<table class="table table-bordered">
    
    <thead>
        <tr>
            <th></th>
            <th>Регион</th>
            <th><span class="glyphicon glyphicon-time"></span></th>
            <th>Дни</th>
            <th>Цена</th>
            <th>Отправлено</th>
            <th><abbr title="Процент маржинальности за последние 5 суток">Марж.</abbr></th>
            <th>Лимит</th>
        </tr>
    </thead>    

    <?php foreach($campaignsArray as $user):?>
        <tr class="active">
            <td colspan="">
                id: <?php echo $user['id'];?>
                <?php echo CHtml::link(CHtml::encode($user['name']), Yii::app()->createUrl('/admin/user/view', array('id'=>$user['id'])));?> 
                <?php if($user['yurcrmToken'] != ''):?>
                <span class="label label-default">CRM</span>
                <?php endif;?>
            </td>
            <td colspan="8">
                    <span class="label label-default balance-<?php echo $user['id'];?>">
            <?php echo $user['balance'];?> руб.</span>

            <div class="buyer-topup-message"></div>
            <a href="#" class="buyer-topup btn btn-xs btn-default" data-id="<?php echo $user['id'];?>">Пополнить</a>

            <form id="buyer-<?php echo $user['id'];?>" data-id="<?php echo $user['id'];?>" class="form-inline form-buyer-topup">
                <div class="form-group">
                    <input type="text" name="sum" style="width:70px" class="form-control input-sm" placeholder="Сумма" />
                </div>
                <div class="form-group">
                    <?php echo CHtml::dropDownList('account', 1, Money::getAccountsArray(), array('class' => 'form-control input-sm'));?>
                </div>
                <a href="#" class="btn  btn-primary btn-sm submit-topup">+</a>
                <br />
                <a href="#" class="buyer-topup-close">Отмена</a>
            </form>
            </td>
        </tr>
    
        <?php foreach($user['campaigns'] as $campaign):?>
            
            <tr>
                <td style="width:200px;">
                </td>
                <td>        
                    <?php echo CHtml::link(CHtml::encode($campaign['regionName'] . ' ' . $campaign['townName']), Yii::app()->createUrl('/admin/campaign/view', array('id'=>$campaign['id'])));?>
                    <small><span class="text-muted">(id:<?php echo $campaign['id'];?>)</span></small>
                </td> 
                <td>
                    <?php echo $campaign['timeFrom'] . '&nbsp;-&nbsp;' . $campaign['timeTo'];?>
                </td>
                <td>
                    <small>
                        <?php 
                            $workDays = array();
                            $workDays = explode(',', $campaign['days']);
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
                            </span>
                        <?php endfor;?>
                        
                    </small>
                </td>

                <td><?php echo $campaign['price'];?> руб.</td>

                <td>
                    <abbr title='Всего'><?php echo $campaign['leadsSent'];?></abbr> / 
                    <abbr title='Сегодня'><?php echo $campaign['todayLeads'];?></abbr> / 
                    <abbr title='Лимит'><?php echo $campaign['leadsDayLimit'];?></abbr>
                </td>
                <td>
                    <?php
                        $revenue = (int)$leadsByStatusArray[$campaign['id']]['revenue'];
                        $expences = (int)$leadsByStatusArray[$campaign['id']]['expences'];
                        $profit = $revenue - $expences;
                        $marginPercent = ($revenue!=0) ? round($profit/$revenue*100) : 0;
                    ?>
                    <?php echo $marginPercent;?>%
                </td>
                <td>
                    <div class="">
                    <?php echo CHtml::textField('realLimit', $campaign['realLimit'], array(
                        'class'=>'form-control set-real-limit input-sm input-xs', 
                        'style' => 'max-width:50px',
                        'data-id' => $campaign['id'],
                        ));?>
                    </div>
                </td>
            </tr>
            <?php endforeach;?>

    <?php    endforeach;?>
</table>