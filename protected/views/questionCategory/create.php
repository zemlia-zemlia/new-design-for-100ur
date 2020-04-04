<?php
/* @var $this QuestionCategoryController */

use App\models\QuestionCategory;

/* @var $model QuestionCategory */

$this->setPageTitle('Создание категории вопросов. ' . Yii::app()->name);

$this->breadcrumbs = [
        'Вопросы и ответы' => ['/question'],
    'Категории вопросов' => ['index'],
    'Новая категория',
];
$this->widget('zii.widgets.CBreadcrumbs', [
    'homeLink' => CHtml::link('CRM', '/'),
    'separator' => ' / ',
    'links' => $this->breadcrumbs,
 ]);
?>

<h1>Новая категория вопросов</h1>

<?php echo $this->renderPartial('_form', ['model' => $model]); ?>