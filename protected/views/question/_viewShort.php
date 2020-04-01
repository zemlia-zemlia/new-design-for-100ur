<div class="questions-short-item row">
    <div class="col-md-2 col-sm-2">
	<small><span class="label label-date">
        <?php //if(!is_null($data->publishDate)) echo CustomFuncs::invertDate($data->publishDate);?>
        <?php use App\helpers\DateHelper;

        if (!is_null($data->publishDate)) {
    echo DateHelper::niceDate($data->publishDate, false);
}?>
	</span></small>
    </div>
  
    
    <div class="col-md-10 col-sm-10">
        <?php if ($data->title):?>
            <?php echo CHtml::link(CHtml::encode($data->title), Yii::app()->createUrl('question/view', ['id' => $data->id])); ?>
        <?php endif; ?>
    </div>    
</div>

