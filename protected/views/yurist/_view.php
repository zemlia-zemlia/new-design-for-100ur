<?php
    $yuristName = ($data->settings && $data->settings->alias!='')?$data->settings->alias:$data->lastName . ' ' . $data->name . ' ' . $data->name2; 
?>
<div class="yurist-list-item">
    <div class="row">
        <div class="col-sm-3">
            <img src="<?php echo $data->getAvatarUrl();?>" alt="<?php echo CHtml::encode($yuristName);?> " class="img-responsive img-bordered" />
        </div>
        <div class="col-sm-9">
            
            <strong class="left-align" style="font-size: 15px;">
                <?php echo CHtml::link(CHtml::encode($yuristName), Yii::app()->createUrl('user/view', array('id'=>$data->id)));?>
            </strong>
            <p class="small">
                <?php if($data->town):?>
                    <strong>Город:</strong> <?php echo $data->town->name;?>
                <?php endif;?>
				<br />
					<strong>Статус:</strong> <?php echo $data->settings->getStatusName();?>
                <br />
					<strong>Карма:</strong> <?php echo (int)$data->karma;?><br />
                
                <?php if($data->answersCount):?>
                    <strong>Ответов:</strong> <?php echo $data->answersCount;?>
                <?php endif;?>
                    
                <?php if($data->settings->priceConsult):?>
                <br />
                <strong>Стоимость консультации:</strong> от <?php echo $data->settings->priceConsult;?> руб.
                <?php endif;?>
                <?php if($data->settings->priceDoc):?>
                    <br />
                    <strong>Стоимость документа:</strong> от <?php echo $data->settings->priceDoc;?> руб.
                <?php endif;?>
                    <br />
					
					
                
                <!-- 
                <?php if(sizeof($data->categories)):?>
                    <strong>Специализации:</strong> 
                <?php endif;?>
                <?php foreach ($data->categories as $cat): ?>
                    <span class="yurist-directions-item"><?php echo $cat->name; ?></span>
                <?php endforeach;?>
                -->                
            </p>
        </div>
    </div>
</div>

