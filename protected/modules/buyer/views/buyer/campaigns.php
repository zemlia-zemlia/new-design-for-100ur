<h1 class="vert-margin20">Мои кампании</h1>
<p>
    <!--
<div class="flat-panel" >
    <div class="inside">
        <?php use App\models\Campaign;

echo CHtml::link('Купленные вручную', Yii::app()->createUrl('/buyer/buyer/leads')); ?>
    </div>
</div>
 -->
</p>




<div class="row">
    <div class="col-md-8">
        <?php if (0 == sizeof(Yii::app()->user->getModel()->campaigns)): ?>
            <div class="callout callout-danger">
                <p>
                    Для того, чтобы начать покупать лиды, Вам
                    необходимо <?php echo CHtml::link('создать кампанию', Yii::app()->createUrl('campaign/create')); ?> и
                    дождаться ее проверки.<br/>
                    Цена лида будет определена модератором при одобрении кампании.<br/>
                    После этого Вы сможете пополнить баланс и получать лиды.
                </p>
            </div>
        <?php endif; ?>

        <div class="box">
            <div class="box-header">
                <div class="box-title">Активные кампании</div>
            </div>
            <div class="box-body">
               <?php $this->renderPartial('_campaignItem', ['campaigns' => $campaigns]) ?>

            </div>
        </div>

        <div class="box">
            <div class="box-header">
                <div class="box-title"> Кампании с другими статусами</div>
            </div>
            <div class="box-body">

                <?php $this->renderPartial('_campaignItem', ['campaigns' => $campaignsNoActive]) ?>

            </div>
        </div>

    </div>
    <div class="col-md-4">
        <div class="box">
            <div class="box-body">
                <p>Если вам нужны заявки из разных регионов или городов, необходимо создать кампании на каждый из
                    них. При этом у всех кампаний будет один единый баланс, с которого будет списываться стоимость лидов.</p>
            </div>
        </div>
        <div class="box">
            <div class="box-body">
                <?php echo CHtml::link('Создать кампанию', Yii::app()->createUrl('campaign/create'), ['class' => 'btn btn-primary btn-block']); ?>
            </div>
        </div>
    </div>
</div>
