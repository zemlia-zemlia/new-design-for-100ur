<?php
/* @var $this AnswerController */
/* @var $model Answer */

$this->setPageTitle("Новый ответ". Yii::app()->name);

$this->breadcrumbs=array(
    'Вопросы и ответы'=>array('/questions'),
    'Новый ответ',
);
$this->widget('zii.widgets.CBreadcrumbs', array(
    'homeLink'=>CHtml::link('CRM', "/"),
    'separator'=>' / ',
    'links'=>$this->breadcrumbs,
 ));
?>

<h1>Новый ответ</h1>

<p class="vert-margin30"><strong>Вопрос:</strong><br />
    <?php echo CHtml::encode($question->questionText); ?>
</p>

<?php echo $this->renderPartial('_form', array(
        'model'         =>  $model,
        'allJurists'    =>  $allJurists,
    )); ?>