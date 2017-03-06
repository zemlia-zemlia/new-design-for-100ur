<?php
/* @var $this MoneyController */
$this->setPageTitle("Финансовый отчет за период. " . Yii::app()->name);

?>

<h1 class="vert-margin20">Финансовый отчет за период</h1>

<div class="vert-margin30">
   <?php $this->renderPartial('_searchReportForm', array('model'=>$searchModel));?> 
</div>

<?php

CustomFuncs::printr($reportDataSet);
?>
