<?php
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
        <?php foreach($model->children as $child):?>
            <p>    
            <?php 
                echo CHtml::link(CHtml::encode($child->title), Yii::app()->createUrl('codecs/view', array('id'=>$child->id)));
            ?>
            </p>
        <?php endforeach;?>
    </div>
    
</div>


