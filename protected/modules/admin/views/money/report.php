<?php
/* @var $this MoneyController */

use App\models\Money;

$this->setPageTitle('Финансовый отчет за период. ' . Yii::app()->name);

?>

<h1 class="vert-margin20">Финансовый отчет за период</h1>

<div class="vert-margin30">
   <?php $this->renderPartial('_searchReportForm', ['model' => $searchModel]); ?> 
</div>

<table class="table">
    <tr><td colspan="2">
        <h3>Доходы</h3>
        </td>
    </tr>
    <?php foreach ($reportDataSetFiltered['income']['directions'] as $code => $value):?>
    <tr>
        <td>
            <?php echo Money::getDirectionByCode($code); ?>
        </td>
        <td>
            <?php echo MoneyFormat::rubles($value); ?>
        </td>
    </tr>
    <?php endforeach; ?>
    <tr>
        <td><strong>Всего доходов</strong></td>
        <td><strong><?php echo MoneyFormat::rubles($reportDataSetFiltered['income']['sum']); ?></strong></td>
    </tr>
    
    <tr>
        <td colspan="2">
            <h3>Расходы</h3>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <h4>Операционные расходы</h4>
        </td>
    </tr>
    <?php foreach ($reportDataSetFiltered['expences']['opex']['directions'] as $code => $value):?>
    <tr>
        <td>
            <?php echo Money::getDirectionByCode($code); ?>
        </td>
        <td>
            <?php echo MoneyFormat::rubles($value); ?>
        </td>
    </tr>
    <?php endforeach; ?>
    <tr>
        <td><strong>Всего операционных расходов</strong></td>
        <td><strong><?php echo MoneyFormat::rubles($reportDataSetFiltered['expences']['opex']['sum']); ?></strong></td>
    </tr>
    
    <tr>
        <td colspan="2">
            <h4>Капитальные расходы</h4>
        </td>
    </tr>
    <?php if ($reportDataSetFiltered['expences']['capex']['directions']):?>
        <?php foreach ($reportDataSetFiltered['expences']['capex']['directions'] as $code => $value):?>
        <tr>
            <td>
                <?php echo Money::getDirectionByCode($code); ?>
            </td>
            <td>
                <?php echo MoneyFormat::rubles($value); ?>
            </td>
        </tr>
        <?php endforeach; ?>
    <?php endif; ?>
    <tr>
        <td><strong>Всего капитальных расходов</strong></td>
        <td><strong><?php echo MoneyFormat::rubles($reportDataSetFiltered['expences']['capex']['sum']); ?></strong></td>
    </tr>
    <tr>
        <td><strong>Всего расходов</strong></td>
        <td><strong><?php echo MoneyFormat::rubles($reportDataSetFiltered['expences']['sum']); ?></strong></td>
    </tr>
    
    <tr>
        <td colspan="2">
            <h3>Прибыли и убытки</h3>
        </td>
    </tr>
    
    <tr>
        <td>EBITDA</td>
        <td><?php echo MoneyFormat::rubles($reportDataSetFiltered['ebitda']); ?></td>
    </tr>
    <tr>
        <td>Чистая прибыль</td>
        <td><?php echo MoneyFormat::rubles($reportDataSetFiltered['net_profit']); ?></td>
    </tr>
</table>
      
