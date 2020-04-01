<?php
/* @var $this QuestionController */

use App\models\Question;

/* @var $model Question */

$this->setPageTitle('Вопросы и ответы - новый вопрос.' . Yii::app()->name);

$this->breadcrumbs = [
    'Вопросы и ответы' => ['index'],
    'Новый вопрос',
];
$this->widget('zii.widgets.CBreadcrumbs', [
    'homeLink' => CHtml::link('CRM', '/'),
    'separator' => ' / ',
    'links' => $this->breadcrumbs,
 ]);
?>

<h1>Добавление вопроса</h1>

<?php echo $this->renderPartial('_form', [
        'model' => $model,
        'allCategories' => $allCategories,
        'categoryId' => $categoryId,
        'townsArray' => $townsArray,
    ]); ?>