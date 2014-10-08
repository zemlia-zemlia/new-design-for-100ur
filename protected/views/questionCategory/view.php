<?php
/* @var $this QuestionCategoryController */
/* @var $model QuestionCategory */

$this->setPageTitle(CHtml::encode($model->name) . ". Категории вопросов. ". Yii::app()->name);

Yii::app()->clientScript->registerMetaTag("Получите бесплатную консультацию юриста. Ответы квалифицированных юристов на вопросы тематики " . CHtml::encode($model->name), 'description');


?>

<h1 class="vert-margin30"><?php echo CHtml::encode($model->name);?></h1>


<div class="center-align vert-margin30">
    <?php echo CHtml::link('Задать вопрос юристу на тему ' . CHtml::encode($model->name), Yii::app()->createUrl('question/create', array('categoryId'=>$model->id)), array('class'=>'btn btn-primary btn-lg'));?>
    <div>Это бесплатно. Вы получите ответ в течение 15 минут</div>
</div>

<h2>Вопросы</h2>

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
