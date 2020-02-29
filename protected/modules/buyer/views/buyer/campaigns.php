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
        <div class="box">
            <div class="box-body">
                <?php foreach ($campaigns

                               as $campaign): ?>

                    <div class="row">
                        <div class="col-md-4">
                            <h5><?php echo CHtml::link($campaign->region->name . ' ' . $campaign->town->name, Yii::app()->createUrl('/buyer/buyer/leads', array('campaign' => $campaign->id))); ?></h5>
                        </div>
                        <div class="col-md-4">
                            <?php if ($campaign->active != Campaign::ACTIVE_MODERATION): ?>
                                <!-- <?php echo $campaign->price; ?> руб. -->
                            <?php endif; ?>

                            <?php echo $campaign->getActiveStatusName(); ?>
                        </div>
                        <div class="col-md-4">
                            <?php echo CHtml::link("Настройки <span class='glyphicon glyphicon-cog'></span>", Yii::app()->createUrl('/buyer/buyer/campaign', array('id' => $campaign->id))); ?>
                        </div>
                    </div>
                <?php endforeach; ?>

            </div>
        </div>

    </div>
    <div class="col-md-4">
        <?php echo CHtml::link('Создать кампанию', Yii::app()->createUrl('campaign/create'), array('class' => 'btn btn-primary btn-block')); ?>

    </div>
</div>
