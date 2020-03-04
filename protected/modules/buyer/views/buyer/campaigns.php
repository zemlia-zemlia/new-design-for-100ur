<h1 class="vert-margin20">Мои кампании</h1>
<p>
    <!--
<div class="flat-panel" >
    <div class="inside">
        <?php echo CHtml::link('Купленные вручную', Yii::app()->createUrl('/buyer/buyer/leads')); ?>
    </div>
</div>
 -->
</p>

<?php if (0 == sizeof(Yii::app()->user->getModel()->campaigns)): ?>
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
        <div class="box">
            <div class="box-body">
                <h1>Активные кампании</h1>
                <?php foreach ($campaigns
                               as $campaign): ?>

                    <div class="row">
                        <div class="col-md-4">
                            <h5><?php echo CHtml::link($campaign->region->name . ' ' . $campaign->town->name, Yii::app()->createUrl('/buyer/buyer/leads', ['campaign' => $campaign->id])); ?></h5>
                        </div>
                        <div class="col-md-4">
                            <?php if (Campaign::ACTIVE_MODERATION != $campaign->active): ?>
                                <!-- <?php echo $campaign->price; ?> руб. -->
                            <?php endif; ?>

                            <?php echo $campaign->getActiveStatusName(); ?>
                        </div>
                        <div class="col-md-4">
                            <?php echo CHtml::link("Настройки <span class='glyphicon glyphicon-cog'></span>", Yii::app()->createUrl('/buyer/buyer/campaign', ['id' => $campaign->id])); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
                <hr>
                <h1>Не активные кампании</h1>
                <?php foreach ($campaignsNoActive
                               as $campaign): ?>

                    <div class="row">
                        <div class="col-md-4">
                            <h5><?php echo CHtml::link($campaign->region->name . ' ' . $campaign->town->name, Yii::app()->createUrl('/buyer/buyer/leads', ['campaign' => $campaign->id])); ?></h5>
                        </div>
                        <div class="col-md-4">
                            <?php if (Campaign::ACTIVE_MODERATION != $campaign->active): ?>
                                <!-- <?php echo $campaign->price; ?> руб. -->
                            <?php endif; ?>

                            <?php echo $campaign->getActiveStatusName(); ?>
                        </div>
                        <div class="col-md-4">
                            <?php echo CHtml::link("Настройки <span class='glyphicon glyphicon-cog'></span>", Yii::app()->createUrl('/buyer/buyer/campaign', ['id' => $campaign->id])); ?>
                        </div>
                    </div>
                <?php endforeach; ?>

            </div>
        </div>

    </div>
    <div class="col-md-4">
        <div class="box">
            <div class="box-body">
                <p>Если вам нужны заявки из разных регионов или городов, необходимо создать кампании на каждый из
                    них.</p>
            </div>
        </div>
        <div class="box">
            <div class="box-body">
                <?php echo CHtml::link('Создать кампанию', Yii::app()->createUrl('campaign/create'), ['class' => 'btn btn-primary btn-block']); ?>
            </div>
        </div>
    </div>
</div>
