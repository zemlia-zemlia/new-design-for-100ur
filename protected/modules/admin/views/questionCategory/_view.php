<?php
/* @var $this QuestionCategoryController */
/* @var $data QuestionCategory */
?>

<tr class="active">
    <td>
        <strong><?php echo CHtml::link(CHtml::encode($data->name), array('view', 'id'=>$data->id)); ?></strong>
        (id <?php echo $data->id;?>) 
        <?php echo CHtml::link("+подкатегория", array('create', 'parentId'=>$data->id), array('class'=>'btn btn-xs btn-default')); ?>
    </td>
    <td>
        <?php echo $data->checkIfPropertyFilled('description1');?>
    </td>
    <td>
        <?php echo $data->checkIfPropertyFilled('description2');?>
    </td>
    <td>
        <?php echo $data->checkIfPropertyFilled('seoH1');?>
    </td>
    <td>
        <?php echo $data->checkIfPropertyFilled('seoTitle');?>
    </td>
    <td>
        <?php echo $data->checkIfPropertyFilled('seoDescription');?>
    </td>
    <td>
        <?php echo $data->checkIfPropertyFilled('seoKeywords');?>
    </td>
    <td>
        <?php echo $data->checkIfPropertyFilled('isDirection');?>
    </td>
    <td>
        <?php echo CHtml::link("Ред.", array('update', 'id'=>$data->id)); ?>
    </td>
</tr>        
        <?php if (sizeof($data->children)):?>
            <?php foreach ($data->children as $child):?>
                <tr>
                    <td>
                    &nbsp;&nbsp;<?php echo CHtml::link(CHtml::encode($child->name), array('view', 'id'=>$child->id)); ?> (id <?php echo $child->id;?>) 
                    </td>
                        <td>
                        <?php echo $child->checkIfPropertyFilled('description1');?>
                    </td>
                    <td>
                        <?php echo $child->checkIfPropertyFilled('description2');?>
                    </td>
                    <td>
                        <?php echo $child->checkIfPropertyFilled('seoH1');?>
                    </td>
                    <td>
                        <?php echo $child->checkIfPropertyFilled('seoTitle');?>
                    </td>
                    <td>
                        <?php echo $child->checkIfPropertyFilled('seoDescription');?>
                    </td>
                    <td>
                        <?php echo $child->checkIfPropertyFilled('seoKeywords');?>
                    </td>
                    <td>
                        <?php echo $child->checkIfPropertyFilled('isDirection');?>
                    </td>
                    <td>
                        <?php echo CHtml::link("Ред.", array('update', 'id'=>$child->id)); ?>
                    </td>
                </tr>
            <?php endforeach;?>
        <?php endif;?>
