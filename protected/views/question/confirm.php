<?php
/*
 * Страница с формой ввода Email автора вопроса
 */

$this->setPageTitle("Подтверждение Email. ". Yii::app()->name);

?>

<div class="flat-panel inside">

<?php $this->renderPartial('_formEmail', array('question'=>$question));?>

</div>