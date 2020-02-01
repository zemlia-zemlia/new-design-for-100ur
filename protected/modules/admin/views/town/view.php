<?php
/* @var $this TownController */
/* @var $model Town */

$this->pageTitle = CHtml::encode($model->name) . ". Города. " . Yii::app()->name;


$this->breadcrumbs=array(
        'Регионы'=>array('/admin/region'),
    CHtml::encode($model->region->name)=>array('/admin/region/view', 'regionAlias'=>CHtml::encode($model->region->alias)),
    $model->name,
);

$this->widget('zii.widgets.CBreadcrumbs', array(
    'homeLink'=>CHtml::link('100 юристов', "/"),
    'separator'=>' / ',
    'links'=>$this->breadcrumbs,
 ));

?>

<h1><?php echo CHtml::encode($model->name); ?>. <?php echo CHtml::encode($model->region->name); ?></h1>

<table class="table table-bordered">
    <tr>
        <td>Столица региона</td>
        <td><?php echo ($model->isCapital)?'Да':'Нет';?></td>    
    </tr>
    <tr>
        <td>Алиас</td>
        <td><?php echo $model->alias;?></td>    
    </tr>
    <tr>
        <td>Население (размер)</td>
        <td><?php echo $model->size;?></td>    
    </tr>
    <tr>
        <td>Вопросов</td>
        <td><?php echo $model->questionsCount;?></td>    
    </tr>
    <tr>
        <td>Описание</td>
        <td><?php echo $model->description;?></td>    
    </tr>
    <tr>
        <td>Описание 1</td>
        <td><?php echo $model->description1;?></td>    
    </tr>
    <tr>
        <td>Описание 2</td>
        <td><?php echo $model->description2;?></td>    
    </tr>
    <tr>
        <td><?php echo $model->getAttributeLabel('seoTitle');?></td>
        <td><?php echo $model->seoTitle;?></td>    
    </tr>
    <tr>
        <td><?php echo $model->getAttributeLabel('seoDescription');?></td>
        <td><?php echo $model->seoDescription;?></td>    
    </tr>
    <tr>
        <td><?php echo $model->getAttributeLabel('seoKeywords');?></td>
        <td><?php echo $model->seoKeywords;?></td>    
    </tr>
    <tr>
        <td><?php echo $model->getAttributeLabel('photo');?></td>
        <td>
            <?php if ($model->photo != ''):?>
                <?php echo CHtml::image($model->getPhotoUrl(), $model->name, array('class'=>'img-responsive'));?>
                <p><br />
                    <?php echo CHtml::link('Удалить фото', Yii::app()->createUrl('/admin/town/removePhoto', array('id'=>$model->id)), array('class'=>'btn btn-danger'));?>
                </p>
            <?php endif;?>
        </td>    
    </tr>
    
</table>

<?php echo CHtml::link('Редактировать', Yii::app()->createUrl('/admin/town/update', array('id'=>$model->id)), array('class'=>'btn btn-primary'));?>
