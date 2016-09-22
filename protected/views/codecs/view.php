<?php
/* @var $this CodecsController */
/* @var $model Codecs */
$this->setPageTitle(CHtml::encode($model->longtitle) . ". Кодексы РФ. ". Yii::app()->name);

Yii::app()->clientScript->registerMetaTag($model->introtext, 'description');


$this->breadcrumbs  =   array(
	'Кодексы РФ'    =>  array('/codecs'),
);

$parents = $model->getParents();

foreach($parents as $parentPath=>$parentTitle) {
    $this->breadcrumbs += array($parentTitle=>array($parentPath));
}

?>

<?php
    $this->widget('zii.widgets.CBreadcrumbs', array(
        'homeLink'=>CHtml::link('Консультация юриста',"/"),
        'separator'=>' &rarr; ',
        'links'=>$this->breadcrumbs,
     ));
?>

<div class="panel panel-default">
    <div class="panel-body">
        <h1><?php echo CHtml::encode($model->longtitle); ?></h1>
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-body">

        <?php
            echo $model->content;
        ?>
    </div>
</div>
