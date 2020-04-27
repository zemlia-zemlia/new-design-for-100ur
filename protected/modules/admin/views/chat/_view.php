<?php
/* @var $this ChatController */
/* @var $data Chat */
?>
<div class='row'>
<div class="col-lg-12">
    <p><a href="<?= Yii::app()->createUrl('/admin/chat/view', ['id' => $model->id]) ?>"><?= $model->user->getShortName()?> -> <?= $model->lawyer->getShortName()?></a></p>
</div>
</div>