<?php
/* @var $this YurCompanyController */
/* @var $model YurCompany */
$this->setPageTitle(CHtml::encode($model->name) . ". Юр. компании. ". Yii::app()->name);

$this->breadcrumbs=array(
        'Управление'=>array('/admin'),
	'Юр. компании'=>array('index'),
	$model->id,
);

$this->widget('zii.widgets.CBreadcrumbs', array(
    'homeLink'=>CHtml::link('100 юристов',"/"),
    'separator'=>' / ',
    'links'=>$this->breadcrumbs,
 ));

?>

<h1><?php echo CHtml::encode($model->name); ?></h1>
<div class="row">
    <div class="col-md-9">
        
        <table class="table table-bordered">
            <tr>
                <td>
                    <?php echo $model->getAttributeLabel('name');?>
                </td>
                <td>
                    <?php echo CHtml::encode($model->name);?>
                </td>
            </tr>
            <tr>
                <td>
                    <?php echo $model->getAttributeLabel('townId');?>
                </td>
                <td>
                    <?php echo CHtml::encode($model->town->name); ?>
                </td>
            </tr>
            <tr>
                <td>
                    <?php echo $model->getAttributeLabel('metro');?>
                </td>
                <td>
                    <?php echo CHtml::encode($model->metro);?>
                </td>
            </tr>
            <tr>
                <td>
                    <?php echo $model->getAttributeLabel('yurName');?>
                </td>
                <td>
                    <?php echo CHtml::encode($model->yurName);?>
                </td>
            </tr>
            <tr>
                <td>
                    Телефоны
                </td>
                <td>
                    <?php echo CHtml::encode($model->phone1) . "<br />";?>
                    <?php if($model->phone2) echo CHtml::encode($model->phone2) . "<br />";?>
                    <?php if($model->phone3) echo CHtml::encode($model->phone3) . "<br />";?>
                </td>
            </tr>
            <tr>
                <td>
                    <?php echo $model->getAttributeLabel('address');?>
                </td>
                <td>
                    <?php echo CHtml::encode($model->address);?>
                </td>
            </tr>
            <tr>
                <td>
                    <?php echo $model->getAttributeLabel('yurAddress');?>
                </td>
                <td>
                    <?php echo CHtml::encode($model->yurAddress);?>
                </td>
            </tr>
            <tr>
                <td>
                    <?php echo $model->getAttributeLabel('description');?>
                </td>
                <td>
                    <?php echo CHtml::encode($model->description);?>
                </td>
            </tr>
            <tr>
                <td>
                    <?php echo $model->getAttributeLabel('yearFound');?>
                </td>
                <td>
                    <?php echo CHtml::encode($model->yearFound);?>
                </td>
            </tr>
            <tr>
                <td>
                    <?php echo $model->getAttributeLabel('website');?>
                </td>
                <td>
                    <?php echo CHtml::encode($model->website);?>
                </td>
            </tr>
        </table>
        
        <?php echo CHtml::link('Редактировать', $this->createUrl('update', array('id'=>$model->id)), array('class'=>'btn btn-primary'));?>
        <?php echo CHtml::link('Удалить', $this->createUrl('delete', array('id'=>$model->id)), array('class'=>'btn btn-danger'));?>

    </div>
    <div class="col-md-3">
        <?php if($model->logo):?>
            <img src="<?php echo $model->getPhotoUrl('thumb');?>" class="img-responsive" alt="" />
            
            <p><br />
                <?php echo CHtml::link('Удалить лого', Yii::app()->createUrl('/admin/yurCompany/removePhoto', array('id'=>$model->id)), array('class'=>'btn btn-danger'));?>
            </p>
        <?php endif;?>
    </div>
</div>
