<?php
/*
 * Страница с формой ввода Email автора вопроса
 */

$this->setPageTitle("Подтверждение Email. ". Yii::app()->name);

?>


<img src="/pics/2017/checkmark_big.png" class="center-block vert-margin30" alt="Ваш вопрос принят" />
<h2 class="text-uppercase">Ваш вопрос принят</h2>

<?php $this->renderPartial('_formEmail', array('question'=>$question));?>

