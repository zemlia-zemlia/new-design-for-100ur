<?php
/* @var $this QuestionCategoryController */
/* @var $model QuestionCategory */

$this->setPageTitle("Редактирование категории вопросов ". $model->id . ". " . Yii::app()->name);


$this->breadcrumbs=array(
        'Вопросы и ответы'=>array('/admin/question'),
	'Категории вопросов'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Редактирование',
);

$this->widget('zii.widgets.CBreadcrumbs', array(
    'homeLink'=>CHtml::link('CRM',"/"),
    'separator'=>' / ',
    'links'=>$this->breadcrumbs,
 ));

?>

<h1>Редактирование категории вопросов 

<?php 
    if(Yii::app()->user->role == User::ROLE_ROOT) {
        echo " ";
        echo CHtml::link("Удалить категорию", Yii::app()->createUrl('/admin/questionCategory/delete', array('id'=>$model->id)),array('class'=>'btn btn-danger', 'onclick'=>'if(!confirm("Вы уверены?")) {return false;}else{return true;}'));
        echo " ";
    }
?>
</h1>



<?php echo $this->renderPartial('_form', array(
        'model'=>$model,
    )); ?>