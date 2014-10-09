<?php
/* @var $this QuestionCategoryController */
/* @var $model QuestionCategory */

$this->setPageTitle(CHtml::encode($model->name) . ". Консультация юриста и адвоката. ". Yii::app()->name);

Yii::app()->clientScript->registerMetaTag("Получите бесплатную консультацию юриста. Ответы квалифицированных юристов на вопросы тематики " . CHtml::encode($model->name), 'description');


?>

<h1 class="vert-margin30"><?php echo CHtml::encode($model->name);?></h1>


<div class="question-form-wrapper">
<h3>Задайте вопрос юристу бесплатно</h3>
<?php
    $this->renderPartial('application.views.question._formSimple', array(
            'model'=>$questionModel,
        ));
?>
</div>

<h2>Вопросы юристу на тему &laquo;<?php echo CHtml::encode($model->name);?>&raquo;</h2>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'  =>  $questionsDataProvider,
	'itemView'      =>  'application.views.question._view',
        'viewData'      =>  array(
            'hideCategory'  =>  true,
        ),
        'emptyText'     =>  'Не найдено ни одного вопроса',
        'summaryText'   =>  '',
        'pager'         =>  array('class'=>'GTLinkPager') //we use own pager with russian words
)); ?>
