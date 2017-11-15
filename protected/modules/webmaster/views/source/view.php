<?php
/* @var $this LeadsourceController */
/* @var $model Leadsource */

$this->breadcrumbs=array(
	'Источники'=>array('index'),
	CHtml::encode($model->name),
);

$this->widget('zii.widgets.CBreadcrumbs', array(
    'homeLink'=>CHtml::link('Кабинет вебмастера',"/webmaster/"),
    'separator'=>' / ',
    'links'=>$this->breadcrumbs,
 ));

?>

<h1>Источник лидов <?php echo CHtml::encode($model->name); ?></h1>

<table class="table table-bordered">
    <tr>
        <td>
            <?php echo $model->getAttributeLabel('id');?>
        </td>
        <td>
            <?php echo $model->id;?>
        </td>
    </tr>
    
    <tr>
        <td>
            <?php echo $model->getAttributeLabel('type');?>
        </td>
        <td>
            <?php echo $model->getTypeName();?>
        </td>
    </tr>
    
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
            <?php echo $model->getAttributeLabel('description');?>
        </td>
        <td>
            <?php echo CHtml::encode($model->description);?>
        </td>
    </tr>
    <?php if($model->type == Leadsource100::TYPE_LEAD):?>
    <tr>
        <td>
            <?php echo $model->getAttributeLabel('appId');?>
        </td>
        <td>
            <?php echo $model->appId;?>
        </td>
    </tr>
    <tr>
        <td>
            <?php echo $model->getAttributeLabel('secretKey');?>
        </td>
        <td>
            <?php echo $model->secretKey;?>
        </td>
    </tr>
    <?php endif;?>
    <tr>
        <td>
            Реферальная ссылка
        </td>
        <td>
            <?php echo Yii::app()->urlManager->baseUrl . '/?partnerAppId=' . $model->appId;?>
        </td>
    </tr>
    
    
</table>