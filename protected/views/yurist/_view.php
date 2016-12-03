<?php
    $yuristName = ($data->settings && $data->settings->alias!='')?$data->settings->alias:$data->lastName . ' ' . $data->name . ' ' . $data->name2; 
?>
<div class="yurist-list-item">
    <div class="row">
        <div class="col-sm-3">
            <img src="<?php echo $data->getAvatarUrl();?>" alt="<?php echo CHtml::encode($yuristName);?>" />
        </div>
        <div class="col-sm-9">
            
            <h4 class="left-align">
                <?php echo CHtml::link(CHtml::encode($yuristName), Yii::app()->createUrl('user/view', array('id'=>$data->id)));?>
            </h4>
            <p>
                <?php if($data->settings->town):?>
                    <?php echo $data->settings->town->name;?>
                    (<?php echo $data->settings->town->region->name;?>) 
                <?php endif;?>
                <br />
                Рейтинг: <?php echo (int)$data->karma;?><br />
                
                <?php if($data->answersCount):?>
                    Ответов: <?php echo $data->answersCount;?>
                    <br />
                <?php endif;?>
                
                <?php if(sizeof($data->categories)):?>
                    Специализации: 
                <?php endif;?>
                <?php foreach ($data->categories as $cat): ?>
                    <span class="yurist-directions-item"><?php echo $cat->name; ?></span>
                <?php endforeach;?>
                                
            </p>
        </div>
    </div>
</div>

