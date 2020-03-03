<?php
/*
 * Страница с формой ввода Email автора вопроса
 * @var Question $question
 */

$this->setPageTitle('Подтверждение Email. ' . Yii::app()->name);

?>

<div class="flat-panel inside">

<?php $this->renderPartial('_formEmail', ['question' => $question]); ?>
<h2>или авторизуйтесь через социальную сеть:</h2>
<?php $this->renderPartial('_formSocials'); ?>

</div>