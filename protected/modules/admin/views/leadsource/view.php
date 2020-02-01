<?php
/* @var $this LeadsourceController */
/* @var $model Leadsource */

$this->breadcrumbs = array(
    'Источники' => array('index'),
    CHtml::encode($model->name),
);

$monthsArray = array(
    '1' => 'Январь',
    '2' => 'Февраль',
    '3' => 'Март',
    '4' => 'Апрель',
    '5' => 'Май',
    '6' => 'Июнь',
    '7' => 'Июль',
    '8' => 'Август',
    '9' => 'Сентябрь',
    '10' => 'Октябрь',
    '11' => 'Ноябрь',
    '12' => 'Декабрь',
);

$this->widget('zii.widgets.CBreadcrumbs', array(
    'homeLink' => CHtml::link('100 Юристов', "/"),
    'separator' => ' / ',
    'links' => $this->breadcrumbs,
));

?>

<h3>Источник лидов <?php echo CHtml::encode($model->name); ?></h3>
<div class="box">
    <table class="table table-bordered">
        <tr>
            <td>
                <?php echo $model->getAttributeLabel('id'); ?>
            </td>
            <td>
                <?php echo $model->id; ?>
            </td>
        </tr>
        <tr>
            <td>
                <?php echo $model->getAttributeLabel('name'); ?>
            </td>
            <td>
                <?php echo CHtml::encode($model->name); ?>
            </td>
        </tr>
        <tr>
            <td>
                <?php echo $model->getAttributeLabel('description'); ?>
            </td>
            <td>
                <?php echo CHtml::encode($model->description); ?>
            </td>
        </tr>
        <tr>
            <td>
                <?php echo $model->getAttributeLabel('appId'); ?>
            </td>
            <td>
                <?php echo $model->appId; ?>
            </td>
        </tr>
        <tr>
            <td>
                <?php echo $model->getAttributeLabel('secretKey'); ?>
            </td>
            <td>
                <?php echo $model->secretKey; ?>
            </td>
        </tr>
    </table>
</div>

<div class="row">
    <div class="col-md-6">
        <h4>Статистика лидов за месяц</h4>
    </div>
    <div class="col-md-6">
        <form class="form-inline vert-margin30" role="form" action="">
            Статистика за месяц:
            <div class="form-group">
                <?php echo CHtml::dropDownList("month", $month, $monthsArray, array('class' => 'form-control')); ?>
            </div>
            <div class="form-group">
                <?php echo CHtml::dropDownList("year", $year, $yearsArray, array('class' => 'form-control')); ?>
            </div>
            <div class="form-group">
                <?php echo CHtml::submitButton("Показать", array('class' => 'btn btn-primary')); ?>
            </div>
        </form>
    </div>
</div>

<div class="box">
    <table class="table table-bordered">
        <tr>
            <th>Город</th>
            <th>Всего</th>
            <th>Брак</th>
            <th>Потрачено</th>
            <th>Продано</th>
            <th>Выручка</th>
            <th>Прибыль</th>
        </tr>
        <?php foreach ($leadsStats as $townName => $townStats): ?>
            <?php
            $sumTotal += (int)$townStats['total'];
            $expTotal += (int)$townStats['expences'];
            $soldTotal += (int)$townStats['sold'];
            $revenueTotal += (int)$townStats['revenue'];
            $brakTotal += (int)$townStats['brak'];
            ?>
            <tr>
                <td><?php echo $townName; ?></td>
                <td><?php echo (int)$townStats['total']; ?></td>
                <td><?php echo (int)$townStats['brak']; ?> (<?php if ($townStats['total']) {
                echo round(((int)$townStats['brak'] / $townStats['total']) * 100);
            } ?>%)
                </td>
                <td><?php echo MoneyFormat::rubles($townStats['expences']); ?></td>
                <td><?php echo $townStats['sold']; ?></td>
                <td><?php echo MoneyFormat::rubles($townStats['revenue']); ?></td>
                <td><?php echo MoneyFormat::rubles($townStats['revenue'] - $townStats['expences']); ?></td>
            </tr>
        <?php endforeach; ?>

        <?php
        $profitTotal = $revenueTotal - $expTotal;
        ?>
        <tr>
            <th>Итого</th>
            <th><?php echo $sumTotal; ?></th>
            <th><?php echo $brakTotal; ?> (<?php if ($sumTotal) {
            echo round(($brakTotal / $sumTotal) * 100);
        } ?>%)
            </th>
            <th><?php echo MoneyFormat::rubles($expTotal); ?> руб.</th>
            <th><?php echo $soldTotal; ?> </th>
            <th><?php echo MoneyFormat::rubles($revenueTotal); ?> руб.</th>
            <th><?php echo MoneyFormat::rubles($profitTotal); ?> руб.</th>
        </tr>
    </table>
</div>