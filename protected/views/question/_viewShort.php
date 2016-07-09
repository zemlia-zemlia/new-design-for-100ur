<div class="questions-short-item row">
    <div class="col-md-4 col-sm-4">
        <?php //if(!is_null($data->publishDate)) echo CustomFuncs::invertDate($data->publishDate);?>
        <?php if(!is_null($data->publishDate)) echo CustomFuncs::niceDate($data->publishDate, false);?>

    </div>
    
    
    <div class="col-md-8 col-sm-8">
        <?php if($data->title):?>
            <?php echo CHtml::link(CHtml::encode($data->title), Yii::app()->createUrl('question/view', array('id'=>$data->id))); ?>
        <?php endif;?>
    </div>    
</div>

