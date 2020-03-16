<?php
/* @var $this CampaignController */

use App\helpers\DateHelper;
use App\models\Campaign;
use App\models\Money;

/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs = [
    'Кампании',
];

$this->pageTitle = 'Кампании. ' . Yii::app()->name;

Yii::app()->clientScript->registerScriptFile('/js/admin/campaign.js');

?>

<style>
    .table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
        padding: 0px;
    }
</style>

<div class="vert-margin20">
    <p>
        <?php echo CHtml::link('Активные', Yii::app()->createUrl('/admin/campaign/index', ['active' => 'active']), ['class' => ('active' == $type) ? 'text-muted' : '']); ?>
        &nbsp;
        <?php echo CHtml::link('Активные ПП', Yii::app()->createUrl('/admin/campaign/index', ['active' => 'active', 'type' => Campaign::TYPE_PARTNERS]), ['class' => ('activePP' == $type) ? 'text-muted' : '']); ?>
        &nbsp;
        <?php echo CHtml::link('Пассивные', Yii::app()->createUrl('/admin/campaign/index', ['active' => 'passive']), ['class' => ('passive' == $type) ? 'text-muted' : '']); ?>
        &nbsp;
        <?php echo CHtml::link('Отключены', Yii::app()->createUrl('/admin/campaign/index', ['active' => 'inactive']), ['class' => ('inactive' == $type) ? 'text-muted' : '']); ?>
        &nbsp;
        <?php echo CHtml::link('Архив', Yii::app()->createUrl('/admin/campaign/index', ['active' => 'archive']), ['class' => ('archive' == $type) ? 'text-muted' : '']); ?>
        &nbsp;
        <?php echo CHtml::link('Одобренные', Yii::app()->createUrl('/admin/campaign/index', ['active' => 'accepted']), ['class' => ('accepted' == $type) ? 'text-muted' : '']); ?>
        &nbsp;

        <?php echo CHtml::link('На модерации', Yii::app()->createUrl('/admin/campaign/index', ['active' => 'moderation']), ['class' => ('moderation' == $type) ? 'text-muted' : '']); ?>
        <span class="badge"><?php echo Campaign::getModerationCount(); ?></span>

        &nbsp;
    </p>
</div>

<div class="box">
    <div class="box-header">
        <div class="box-title">Кампании</div>
    </div>
    <div class="box-body">
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

            <?php foreach ($campaignsArray as $user): ?>
                <tr class="active">
                    <td colspan="">
                        id: <?php echo $user['id']; ?>
                        <?php echo CHtml::link(CHtml::encode($user['name']), Yii::app()->createUrl('/admin/user/view', ['id' => $user['id']])); ?>
                        <?php if ('' != $user['yurcrmToken']): ?>
                            <span class="label label-default">CRM</span>
                        <?php endif; ?>
                    </td>
                    <td colspan="8">
                    <span class="label label-default balance-<?php echo $user['id']; ?>">
            <?php echo MoneyFormat::rubles($user['balance']); ?> руб.</span>

                        <div class="buyer-topup-message"></div>
                        <a href="#" class="buyer-topup btn btn-xs btn-default"
                           data-id="<?php echo $user['id']; ?>">Пополнить</a>

                        <form id="buyer-<?php echo $user['id']; ?>" data-id="<?php echo $user['id']; ?>"
                              class="form-inline form-buyer-topup">
                            <div class="form-group">
                                <input type="text" name="sum" style="width:70px" class="form-control input-sm"
                                       placeholder="Сумма"/>
                            </div>
                            <div class="form-group">
                                <?php echo CHtml::dropDownList('account', 1, Money::getAccountsArray(), ['class' => 'form-control input-sm']); ?>
                            </div>
                            <br/>
                            <a href="#" class="btn btn-block btn-primary btn-sm submit-topup">Зачислить</a>
                            <br/>
                            <a href="#" class="btn btn-block  btn-danger btn-xs buyer-topup-close">Отмена</a>
                        </form>
                    </td>
                </tr>

                <?php foreach ($user['campaigns'] as $campaign): ?>

                    <tr>
                        <td style="width:230px;">
                        </td>
                        <td>
                            <?php echo CHtml::link(CHtml::encode($campaign['regionName'] . ' ' . $campaign['townName']), Yii::app()->createUrl('/admin/campaign/view', ['id' => $campaign['id']])); ?>
                            <small><span class="text-muted">(id:<?php echo $campaign['id']; ?>)</span></small>
                        </td>
                        <td>
                            <?php echo $campaign['timeFrom'] . '&nbsp;-&nbsp;' . $campaign['timeTo']; ?>
                        </td>
                        <td>
                            <small>
                                <?php
                                $workDays = [];
                                $workDays = explode(',', $campaign['days']);
                                ?>

                                <?php for ($dayNumber = 1; $dayNumber <= 7; ++$dayNumber): ?>
                                    <?php
                                    if (!in_array($dayNumber, $workDays)) {
                                        $labelClass = 'label-default';
                                    } else {
                                        $labelClass = ($dayNumber > 5) ? 'label-danger' : 'label-success';
                                    }
                                    ?>
                                    <span class="label <?php echo $labelClass; ?>">
                                <?php echo DateHelper::getWeekDays()[$dayNumber]; ?>
                            </span>
                                <?php endfor; ?>

                            </small>
                        </td>

                        <td><?php echo MoneyFormat::rubles($campaign['price']); ?> руб.</td>

                        <td>
                            <abbr title='Всего'><?php echo $campaign['leadsSent']; ?></abbr> /
                            <abbr title='Сегодня'><?php echo $campaign['todayLeads']; ?></abbr> /
                            <abbr title='Лимит'><?php echo $campaign['leadsDayLimit']; ?></abbr>
                        </td>
                        <td>
                            <?php
                            $revenue = (int) $leadsByStatusArray[$campaign['id']]['revenue'];
                            $expences = (int) $leadsByStatusArray[$campaign['id']]['expences'];
                            $profit = $revenue - $expences;
                            $marginPercent = (0 != $revenue) ? round($profit / $revenue * 100) : 0;
                            ?>
                            <?php echo $marginPercent; ?>%
                        </td>
                        <td>
                            <div class="">
                                <?php echo CHtml::textField('realLimit', $campaign['realLimit'], [
                                    'class' => 'form-control set-real-limit input-sm input-xs',
                                    'style' => 'max-width:50px',
                                    'data-id' => $campaign['id'],
                                ]); ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>

            <?php endforeach; ?>
        </table>
    </div>
</div>