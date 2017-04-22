<?php
$this->setPageTitle("Быстрое редактирование вопроса " . $model->id);
Yii::app()->clientScript->registerScriptFile('/js/admin/question.js');

?>

<h1>Редактирование вопроса <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_formModerate', array(
        'model'         =>  $model,
    )); ?>