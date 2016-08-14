<?php
/* @var $this RegionController */
/* @var $model Region */

$pageTitle = "Юристы и Адвокаты " . CHtml::encode($model->name) . '.';
Yii::app()->clientScript->registerMetaTag("Каталог и рейтинг Юристов и Адвокатов " . CHtml::encode($model->name), "Description");

$this->setPageTitle($pageTitle . Yii::app()->name);

$this->breadcrumbs=array(
	'Регионы'   =>  array('/region'),
	CHtml::encode($model->name),
);
?>

<?php
    $this->widget('zii.widgets.CBreadcrumbs', array(
        'homeLink'=>CHtml::link('Консультация юриста',"/"),
        'separator'=>' / ',
        'links'=>$this->breadcrumbs,
     ));
?>

<div class='panel gray-panel'>
    <div class="panel-body">
        <h1 class="vert-margin30"><?php echo CHtml::encode($model->name); ?></h1>

        <?php if(is_array($model->towns) && sizeof($model->towns)):?>

                <div class="row">
                    <?php foreach($model->towns as $town):?>
                        <div class="col-md-4">
                            <?php echo CHtml::link('<span class="glyphicon glyphicon-map-marker"></span>' . $town->name, 
                                    Yii::app()->createUrl('town/alias', array(
                                        'name'          =>  $town->alias,
                                        'countryAlias'  =>  $town->country->alias,
                                        'regionAlias'   =>  $town->region->alias,
                                        )));?>
                        </div>    
                    <?php endforeach;?>
                    </div>

        <?php endif;?>
        
    </div>
</div>

