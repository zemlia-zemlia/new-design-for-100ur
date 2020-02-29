<?php
/**
 * @var $soldLeadsCount int
 * @var $averageExpencesPerDay int
 */

$this->setPageTitle("Кабинет покупателя лидов. " . Yii::app()->name);

?>

<?php if (sizeof(Yii::app()->user->getModel()->campaigns) == 0): ?>
    <div class="alert alert-danger">
        <p>
            Для того, чтобы начать покупать лиды, Вам
            необходимо <?php echo CHtml::link('создать кампанию', Yii::app()->createUrl('campaign/create')); ?> и
            дождаться ее проверки.<br/>
            Цена лида будет определена модератором при одобрении кампании.<br/>
            После этого Вы сможете пополнить баланс и получать лиды.
        </p>
    </div>
<?php endif; ?>

<div class="row">
    <div class="col-md-8">
        <h2>Мои лиды</h2>
        <?php $this->widget('zii.widgets.CListView', array(
            'dataProvider' => $dataProvider,
            'itemView' => '_viewLead',
            'emptyText' => 'Не найдено ни одного лида',
            'summaryText' => 'Показаны лиды с {start} до {end}, всего {count}',
            'pager' => array('class' => 'GTLinkPager') //we use own pager with russian words
        )); ?>
    </div>
    <div class="col-md-4">
        <h2>Статистика:</h2>
        <div class="row">
            <div class="col-lg-12 col-xs-12">
                <!-- small box -->
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3><?php echo $soldLeadsCount;?></h3>
                        <p>Лидов за 30 дней</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-bars"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 col-xs-12">
                <!-- small box -->
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h3><?php echo MoneyFormat::rubles($averageExpencesPerDay);?> </h3>

                        <p>Средний расход в день</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-rub"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php
if (!$showInactive) {
    echo CHtml::link('Показать неактивные', $this->createUrl('?show_inactive=true'));
}
?>

