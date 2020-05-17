<?php
/* @var $this ChatController */
/* @var $data Chat */
?>
<div class='row'>
<div class="col-lg-12">
    <p><a href="<?php echo Yii::app()->createUrl('/admin/chat/view', ['id' => $model->id]); ?>"><?php echo $model->user->getShortName(); ?> -> <?php echo $model->lawyer->getShortName(); ?></a></p>
</div>
</div>