<?php
/* @var $this MoneyController */

use App\models\PartnerTransaction;

/* @var $dataProvider CActiveDataProvider */
$this->setPageTitle('Касса. ' . Yii::app()->name);

$sumBalance = 0;

?>

<style>
    .table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
        padding: 3px;
    }
</style>

<h3>Касса
    <?php echo CHtml::link('Добавить запись', Yii::app()->createUrl('/admin/money/create'), ['class' => 'btn btn-primary']); ?>
    <?php echo CHtml::link('Отчет', Yii::app()->createUrl('/admin/money/report'), ['class' => 'btn btn-default']); ?>
    <?php echo CHtml::link('Внутр. транз.', Yii::app()->createUrl('/admin/money/addTransaction'), ['class' => 'btn btn-primary']); ?>
    <small>
        <abbr title="Сумма балансов вебмастеров">
            <?php echo MoneyFormat::rubles(PartnerTransaction::sumAll()); ?> руб.
        </abbr>
    </small>
</h3>

<div class="box">
    <div class="box-body">
        <div class="row">
            <?php foreach ($accounts as $acId => $account): ?>
                <div class="col-sm-2 col-xs-4 center-align">
                    <div class="panel panel-warning">
                        <div class="panel-heading"><?php echo $account; ?></div>
                        <div class="panel-body">
                            <h4><?php echo MoneyFormat::rubles($balances[$acId]); ?></h4>
                        </div>
                    </div>
                </div>
                <?php $sumBalance += $balances[$acId]; ?>
            <?php endforeach; ?>
            <div class="col-sm-2 col-xs-4 center-align">
                <div class="panel panel-success">
                    <div class="panel-heading">Всего</div>
                    <div class="panel-body">
                        <h4><?php echo MoneyFormat::rubles($sumBalance); ?></h4>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<div class="vert-margin20">
    <?php $this->renderPartial('_search', ['model' => $searchModel]); ?>
</div>
<div class="box">
    <div class="box-body">
        <small>
            <table class="table table-bordered">

                <tr>
                    <th>#</th>
                    <th>Дата</th>
                    <th>Статья</th>
                    <th>Счет</th>
                    <th>Сумма</th>
                    <th style="width: 550px;">Комментарий</th>
                </tr>
                <?php $this->widget('zii.widgets.CListView', [
                    'dataProvider' => $dataProvider,
                    'itemView' => '_view',
                    'emptyText' => 'Не найдено ни одной записи',
                    'summaryText' => 'Показаны записи с {start} до {end}, всего {count}',
                    'pager' => ['class' => 'GTLinkPager'], //we use own pager with russian words
                ]); ?>
            </table>
        </small>
    </div>
</div>