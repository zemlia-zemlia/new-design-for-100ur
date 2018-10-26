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


<div class="row">
	<div class="col-md-9 small">
	<br/>
	<h1 class="vert-margin30"><?php echo CHtml::encode($model->name);?></h1>
	<br/>
	<?php if ($model->description1): ?>
		<div class="vert-margin30">
			<?php echo $model->description1; ?>
		</div>
	<?php endif; ?>
	
	<h3>Вложенные категории:</h3>

<?php $this->renderPartial('_table', ['categoriesArray' => $subCategoriesArray]);?>
	</div>


<div class="col-md-3">
    <?php echo CHtml::link('Редактировать категорию', Yii::app()->createUrl('/admin/questionCategory/update', array('id'=>$model->id)), array('class'=>'btn btn-block btn-primary'));?>
	<?php echo CHtml::link('Открыть на сайте', Yii::app()->createUrl('/questionCategory/alias', array('name'=>$model->alias)), array('class'=>'btn btn-block btn-default', 'target' => '_blank'));?>
	<br/>
	<p>Заглавная картинка категории:</p>
	<?php if($model->image):?>
		<?php echo CHtml::image($model->getImagePath(), '', ['class' => 'img-responsive']);?>
	<?php endif;?>
	


</div>

</div>









