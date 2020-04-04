<?php
/* @var $this QuestionCategoryController */

use App\models\QuestionCategory;

/* @var $data QuestionCategory */
?>


<tr class="active">
    <td>
        <strong><?php echo CHtml::link(CHtml::encode($data->name), ['view', 'id' => $data->id]); ?></strong>
        (id <?php echo $data->id; ?>) 
        <?php echo CHtml::link('+подкатегория', ['create', 'parentId' => $data->id], ['class' => 'btn btn-xs btn-default']); ?>
    </td>
    <td>
        <?php echo $data->checkIfPropertyFilled('description1'); ?>
    </td>
    <td>
        <?php echo $data->checkIfPropertyFilled('description2'); ?>
    </td>
    <td>
        <?php echo $data->checkIfPropertyFilled('seoH1'); ?>
    </td>
    <td>
        <?php echo $data->checkIfPropertyFilled('seoTitle'); ?>
    </td>
    <td>
        <?php echo $data->checkIfPropertyFilled('seoDescription'); ?>
    </td>
    <td>
        <?php echo $data->checkIfPropertyFilled('seoKeywords'); ?>
    </td>
    <td>
        <?php echo $data->checkIfPropertyFilled('isDirection'); ?>
    </td>
    <td>
        <?php echo CHtml::link('Ред.', ['update', 'id' => $data->id]); ?>
    </td>
</tr>        
        <?php if (sizeof($data->children)):?>
            <?php foreach ($data->children as $child):?>
                <tr>
                    <td>
                    &nbsp;&nbsp;<?php echo CHtml::link(CHtml::encode($child->name), ['view', 'id' => $child->id]); ?> (id <?php echo $child->id; ?>) 
                    </td>
                        <td>
                        <?php echo $child->checkIfPropertyFilled('description1'); ?>
                    </td>
                    <td>
                        <?php echo $child->checkIfPropertyFilled('description2'); ?>
                    </td>
                    <td>
                        <?php echo $child->checkIfPropertyFilled('seoH1'); ?>
                    </td>
                    <td>
                        <?php echo $child->checkIfPropertyFilled('seoTitle'); ?>
                    </td>
                    <td>
                        <?php echo $child->checkIfPropertyFilled('seoDescription'); ?>
                    </td>
                    <td>
                        <?php echo $child->checkIfPropertyFilled('seoKeywords'); ?>
                    </td>
                    <td>
                        <?php echo $child->checkIfPropertyFilled('isDirection'); ?>
                    </td>
                    <td>
                        <?php echo CHtml::link('Ред.', ['update', 'id' => $child->id]); ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
