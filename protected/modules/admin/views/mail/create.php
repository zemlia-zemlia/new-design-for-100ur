<?php
    $this->setPageTitle('Новая рассылка. ' . Yii::app()->name);
?>

<h1>Новая рассылка</h1>

<?php echo $this->renderPartial('_form', ['model' => $mailModel]); ?>