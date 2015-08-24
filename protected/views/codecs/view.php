<?php
/* @var $this CodecsController */
/* @var $model Codecs */

$this->setPageTitle(CHtml::encode($model->title) . ". Кодексы РФ. ". Yii::app()->name);


$this->breadcrumbs=array(
	'Кодексы РФ'=>array('index'),
	$model->title,
);

?>

<?php
    $this->widget('zii.widgets.CBreadcrumbs', array(
        'homeLink'=>CHtml::link('Консультация юриста',"/"),
        'separator'=>' &rarr; ',
        'links'=>$this->breadcrumbs,
     ));
?>

<h1><?php echo CHtml::encode($model->title); ?></h1>

<div class="panel">
    <div class="panel-body">
        <?php
            echo $model->content;
        ?>
    </div>
</div>
