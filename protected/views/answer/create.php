<?php
/* @var $this AnswerController */

use App\models\Answer;

/* @var $model Answer */

$this->setPageTitle('Новый ответ' . Yii::app()->name);

$this->breadcrumbs = [
    'Вопросы и ответы' => ['/questions'],
    'Новый ответ',
];
$this->widget('zii.widgets.CBreadcrumbs', [
    'homeLink' => CHtml::link('CRM', '/'),
    'separator' => ' / ',
    'links' => $this->breadcrumbs,
 ]);
?>

<h1>Новый ответ</h1>

<p class="vert-margin30"><strong>Вопрос:</strong><br />
    <?php echo CHtml::encode($question->questionText); ?>
</p>

<?php echo $this->renderPartial('_form', [
        'model' => $model,
        'allJurists' => $allJurists,
    ]); ?>