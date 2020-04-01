<?php
/* @var $this QuestionController */

use App\models\Question;

/* @var $model Question */

$this->setPageTitle('Вопросы и ответы - редактировать вопрос. ' . Yii::app()->name);

$this->breadcrumbs = [
    'Вопросы и ответы' => ['index'],
    $model->id => ['view', 'id' => $model->id],
    'Редактирование',
];

$this->widget('zii.widgets.CBreadcrumbs', [
    'homeLink' => CHtml::link('CRM', '/'),
    'separator' => ' / ',
    'links' => $this->breadcrumbs,
 ]);

?>

<h1>Редактирование вопроса <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', [
        'model' => $model,
        'allCategories' => $allCategories,
        'townsArray' => $townsArray,
    ]); ?>