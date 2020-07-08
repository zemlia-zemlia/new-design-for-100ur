<?php
//    Yii::app()->clientScript->registerScriptFile('/js/profile_notifier.js', CClientScript::POS_END);
?>

<div class="blue-block text-center alert-dismissible">
    <div class="inside">
            <button type="button" class="close close-profile-notifier" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <?php echo $message; ?>
    </div>
</div>

