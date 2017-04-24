<?php
$this->setPageTitle("Быстрое редактирование вопроса " . $model->id);
Yii::app()->clientScript->registerScriptFile('/js/admin/question.js');

?>

<h1>Редактирование вопроса <?php echo $model->id; ?> (осталось <?php echo $questionsCount;?>)</h1>
<p class="text-center">
    Вы отредактировали <?php  echo $questionsModeratedByMeCount . ' ' . CustomFuncs::numForms($questionsModeratedByMeCount, 'вопрос', 'вопроса', 'вопросов');?>
</p>

<?php echo $this->renderPartial('_formModerate', array(
        'model'         =>  $model,
    )); ?>