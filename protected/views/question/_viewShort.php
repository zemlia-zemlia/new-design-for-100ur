<div class="questions-short-item row">
    <div class="col-md-3 col-sm-3">
        <?php if(!is_null($data->publishDate)) echo CustomFuncs::invertDate($data->publishDate);?>
    </div>
    
    <div class="col-md-3 col-sm-3">
        <img src="/pics/2015/icon_comment.png" alt='' />&nbsp;<?php echo $data->answersCount . "&nbsp;" .  CustomFuncs::numForms($data->answersCount, 'ответ', 'ответа', 'ответов');?>
    </div>
    
    <div class="col-md-6 col-sm-6">
        <?php if($data->title):?>
            <?php echo CHtml::link(CHtml::encode($data->title), Yii::app()->createUrl('question/view', array('id'=>$data->id))); ?>
        <?php endif;?>
    </div>    
</div>

