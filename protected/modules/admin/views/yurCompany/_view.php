<?php
/* @var $this YurCompanyController */
/* @var $data YurCompany */
?>

<tr>
    <td>
        <?php if($data->logo):?>
            <img src="<?php echo $data->getPhotoUrl('thumb');?>" class="img-responsive" alt="" />
        <?php endif;?>
    </td>
    <td>
        <?php echo CHtml::link(CHtml::encode($data->name), Yii::app()->createUrl('/admin/yurCompany/view', array('id'=>$data->id)));?>
    </td>
    <td>
        <?php echo CHtml::encode($data->town->name); ?>
    </td>
    <td>
        <?php echo CHtml::encode($data->website);?>
    </td>
    <td>
        <?php
        if($data->author instanceof User){
            echo CHtml::encode($data->author->name);
        }
        ?>
    </td>
</tr>