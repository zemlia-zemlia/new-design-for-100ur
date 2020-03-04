<?php
    $this->setPageTitle('Превышен лимит отбраковки лидов. ' . Yii::app()->name);
?>


<h1>Превышен лимит отбраковки лидов</h1>

<div class="alert alert-warning">

    <p>
        Достигнут лимит отбраковки лидов <br/> (вы можете отбраковать не более <?php echo $campaign->brakPercent; ?>% лидов, поступивших за этот день)
    </p>
    <p>
        <strong>Благодарим за сотрудничество!</strong>
    </p>
</div>