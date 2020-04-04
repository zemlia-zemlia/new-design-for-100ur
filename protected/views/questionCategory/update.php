<?php
/* @var $this QuestionCategoryController */

use App\models\QuestionCategory;

/* @var $model QuestionCategory */

$this->setPageTitle('Редактирование категории вопросов ' . $model->id . '. ' . Yii::app()->name);

$this->breadcrumbs = [
        'Вопросы и ответы' => ['/question'],
    'Категории вопросов' => ['index'],
    $model->name => ['view', 'id' => $model->id],
    'Редактирование',
];

$this->widget('zii.widgets.CBreadcrumbs', [
    'homeLink' => CHtml::link('CRM', '/'),
    'separator' => ' / ',
    'links' => $this->breadcrumbs,
 ]);

?>

<h1>Редактирование категории вопросов</h1>

<?php echo $this->renderPartial('_form', [
        'model' => $model,
    ]); ?>