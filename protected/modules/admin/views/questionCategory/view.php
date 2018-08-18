<?php
/* @var $this QuestionCategoryController */
/* @var $model QuestionCategory */

$this->setPageTitle(CHtml::encode($model->id) . ". Категории вопросов. ". Yii::app()->name);

$this->breadcrumbs=array(
        'Вопросы и ответы'=>array('/admin/question'),
	'Категории вопросов'=>array('index'),
	CHtml::encode($model->name),
);

$this->widget('zii.widgets.CBreadcrumbs', array(
    'homeLink'=>CHtml::link('100 юристов',"/admin/"),
    'separator'=>' / ',
    'links'=>$this->breadcrumbs,
 ));
?>

<h1 class="vert-margin30"><?php echo CHtml::encode($model->name);?>
    <?php echo CHtml::link('Редактировать категорию', Yii::app()->createUrl('/admin/questionCategory/update', array('id'=>$model->id)), array('class'=>'btn btn-primary'));?>
	
	    <?php echo CHtml::link('Смотреть на сайте', Yii::app()->createUrl('/questionCategory/alias', array('name'=>$model->alias)), array('class'=>'btn btn-default', 'target' => '_blank'));?>
</h1>

<h3>Вложенные категории:</h3>

<?php $this->renderPartial('_table', ['categoriesArray' => $subCategoriesArray]);?>

