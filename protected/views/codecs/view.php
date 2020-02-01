<?php
/* @var $this CodecsController */
/* @var $model Codecs */
$this->setPageTitle(CHtml::encode($model->longtitle) . ". Кодексы РФ. ". Yii::app()->name);

Yii::app()->clientScript->registerMetaTag($model->introtext, 'description');


$this->breadcrumbs  =   array(
    'Кодексы РФ'    =>  array('/codecs'),
);

$parents = $model->getParents();

foreach ($parents as $parentPath=>$parentTitle) {
    $this->breadcrumbs += array($parentTitle=>array($parentPath));
}

?>

<?php
    $this->widget('zii.widgets.CBreadcrumbs', array(
        'homeLink'=>CHtml::link('Консультация юриста', "/"),
        'separator'=>' &rarr; ',
        'links'=>$this->breadcrumbs,
     ));
?>


<h1 class="header-block header-block-light-grey vert-margin30"><?php echo CHtml::encode($model->longtitle); ?></h1>



        <?php
            echo $model->content;
        ?>

