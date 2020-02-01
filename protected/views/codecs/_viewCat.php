<p>
<?php
    echo CHtml::link(CHtml::encode($data->title), Yii::app()->createUrl('codecs/view', array('id'=>$data->id)));
?>
</p>