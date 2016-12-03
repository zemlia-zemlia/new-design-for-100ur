<?php
/* @var $this CommentController */
/* @var $model Comment */
$this->setPageTitle("Редактирование отзыва.". Yii::app()->name);


?>

<h1>Редактирование отзыва <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('application.views.comment._form', array('model'=>$model)); ?>