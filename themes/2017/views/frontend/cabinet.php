<?php
CController::renderPartial('webroot.themes.2017.views.frontend.include._header');
?>
    
    <nav class="navbar navbar-inverse vert-margin20">
        <div id="top-menu-wrapper">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbar-collapse">
            <ul class="nav navbar-nav">
                <li><?php echo ($_SERVER['REQUEST_URI'] != '/cabinet/')?CHtml::link('Главная', '/cabinet/'):'<span class="active">Главная</span>';?></li>
                <!-- <li><?php echo ($_SERVER['REQUEST_URI'] != '/lead/')?CHtml::link('Каталог лидов', '/lead/'):'<span class="active">Каталог лидов</span>';?></li> -->
                <li><?php echo ($_SERVER['REQUEST_URI'] != '/cabinet/transactions/')?CHtml::link('Баланс', Yii::app()->createUrl('/cabinet/transactions/')):'<span class="active">Баланс</span>';?></li>
                <li><?php echo ($_SERVER['REQUEST_URI'] != '/cabinet/api/')?CHtml::link('API', Yii::app()->createUrl('/cabinet/api/')):'<span class="active">API</span>';?></li>
                <li><?php echo ($_SERVER['REQUEST_URI'] != '/cabinet/faq/')?CHtml::link('FAQ', Yii::app()->createUrl('/cabinet/faq/')):'<span class="active">FAQ</span>';?></li>
				<li><a href="http://www.yurcrm.ru/" target="_blank" rel="nofollow">CRM для юристов</a></li>
				<li><?php echo ($_SERVER['REQUEST_URI'] != '/cabinet/help/')?CHtml::link('Техподдержка', Yii::app()->createUrl('/cabinet/help/')):'<span class="active">Техподдержка</span>';?></li>

            </ul>
            </div>
        </div>
    </nav>
    
        

    
    <div id="middle ">
        <div class="container-fluid container">
                
			
            <div class="col-md-3 col-sm-4">
                <h1 class="vert-margin20">Мои кампании</h1>	
				<p>
                <?php $campaigns = Campaign::getCampaignsForBuyer(Yii::app()->user->id);?>
                
                <?php echo CHtml::link('Создать кампанию', Yii::app()->createUrl('campaign/create'), array('class' => 'btn btn-primary btn-block'));?>
                
                <div class="flat-panel" >
                    <div class="inside">
                        <?php echo CHtml::link('Купленные вручную', Yii::app()->createUrl('cabinet/leads'));?>
                    </div>
                </div>
                                
                <?php foreach($campaigns as $campaign):?>
				</p>
                <div class="flat-panel" >
                    <div class="inside">
                        <h5>
                            <?php echo CHtml::link($campaign->region->name . ' ' . $campaign->town->name, Yii::app()->createUrl('/cabinet/leads', array('campaign'=>$campaign->id)));?>
                            
                            <?php if($campaign->active != Campaign::ACTIVE_MODERATION):?>
                                <!-- <?php echo $campaign->price;?> руб. -->
                            <?php endif;?>
                            
                            <?php echo CHtml::link("<span class='glyphicon glyphicon-cog'></span>", Yii::app()->createUrl('/cabinet/campaign', array('id'=>$campaign->id)));?> 
							
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
            
            <div class="flat-panel inside col-md-9 col-sm-8">
                <?php echo $content;?>
            </div>
            				
        </div>
     </div>
    
<?php
CController::renderPartial('webroot.themes.2017.views.frontend.include._footer');
?>