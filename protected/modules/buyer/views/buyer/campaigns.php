
<div>
                <h1 class="vert-margin20">Мои кампании</h1>
				<p>


                <?php echo CHtml::link('Создать кампанию', Yii::app()->createUrl('campaign/create'), array('class' => 'btn btn-primary btn-block'));?>
                <!--
                <div class="flat-panel" >
                    <div class="inside">
                        <?php echo CHtml::link('Купленные вручную', Yii::app()->createUrl('buyer/leads'));?>
                    </div>
                </div>
                 -->
                <?php foreach($campaigns as $campaign):?>
				</p>
                <div class="flat-panel" >
                    <div class="inside">
                        <h5>
                            <?php echo CHtml::link($campaign->region->name . ' ' . $campaign->town->name, Yii::app()->createUrl('/buyer/leads', array('campaign'=>$campaign->id)));?>

                            <?php if($campaign->active != Campaign::ACTIVE_MODERATION):?>
                                <!-- <?php echo $campaign->price;?> руб. -->
                            <?php endif;?>

                            <?php echo CHtml::link("<span class='glyphicon glyphicon-cog'></span>", Yii::app()->createUrl('/buyer/campaign', array('id'=>$campaign->id)));?>

							<?php if($campaign->active != Campaign::ACTIVE_YES):?>
							<p>
								<?php echo $campaign->getActiveStatusName();?>
							</p>
							<?php endif;?>
                        </h5>


                    </div>
                </div>
                <?php endforeach;?>
            </div>