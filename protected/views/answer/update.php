<?php
/* @var $this AnswerController */
/* @var $model Answer */
$this->setPageTitle('Редактирование текста ответа ' . $model->id . '. ' . Yii::app()->name);

?>

<h1>Редактирование ответа</h1>

<h3>Текст вопроса</h3>
<p>
    <?php echo CHtml::encode($model->question->questionText); ?>
</p>

<?php echo $this->renderPartial('application.views.answer._form', [
    'model' => $model,
    ]);
?>