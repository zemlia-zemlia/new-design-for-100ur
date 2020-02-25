<h1 class="vert-margin20">Мои кампании</h1>
<p>


    <!--
                <div class="flat-panel" >
                    <div class="inside">
                        <?php echo CHtml::link('Купленные вручную', Yii::app()->createUrl('buyer/leads')); ?>
                    </div>
                </div>
                 -->
</p>

<div class="row">
    <div class="col-md-8">
        <div class="box">
            <div class="box-body">
                <?php foreach ($campaigns

                               as $campaign): ?>

                    <div class="row">
                        <div class="col-md-4">
                            <h5><?php echo CHtml::link($campaign->region->name . ' ' . $campaign->town->name, Yii::app()->createUrl('/buyer/leads', array('campaign' => $campaign->id))); ?></h5>
                        </div>
                        <div class="col-md-4">
                            <?php if ($campaign->active != Campaign::ACTIVE_MODERATION): ?>
                                <!-- <?php echo $campaign->price; ?> руб. -->
                            <?php endif; ?>

                            <?php echo $campaign->getActiveStatusName(); ?>
                        </div>
                        <div class="col-md-4">
                            <?php echo CHtml::link("Настройки <span class='glyphicon glyphicon-cog'></span>", Yii::app()->createUrl('/buyer/campaign', array('id' => $campaign->id))); ?>
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
