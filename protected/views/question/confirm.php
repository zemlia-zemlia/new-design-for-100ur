<?php
/*
 * Страница с формой ввода Email автора вопроса
 */

$this->setPageTitle("Подтверждение Email. ". Yii::app()->name);

?>

<div class="flat-panel inside">
<img src="/pics/2017/checkmark_big.png" class="center-block vert-margin30" alt="Ваш вопрос принят" />

<?php $this->renderPartial('_formEmail', array('question'=>$question));?>

</div>