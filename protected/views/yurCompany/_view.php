<div class="row company-list-item">
    <div class="col-md-3">
        <?php if($data->logo):?>
        <?php echo CHtml::link(CHtml::image($data->getPhotoUrl('thumb'), CHtml::encode($data->name), array('class'=>'img-responsive')), Yii::app()->createUrl('yurCompany/view',array('id'=>$data->id)));?>
        <?php endif;?>
    </div>
    <div class="col-md-9 company-list-description">
        <?php echo CHtml::link(CHtml::encode($data->name), Yii::app()->createUrl('yurCompany/view',array('id'=>$data->id)));?>
        <p class="text-muted">
            <span class="glyphicon glyphicon-map-marker"></span> <?php echo CHtml::encode($data->address);?>
        </p>
    </div>
</div>

