<?php
$this->setPageTitle("Направления вопросов. ". Yii::app()->name);
Yii::app()->clientScript->registerScriptFile('/js/admin/directions.js');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>

<h1>Направления вопросов</h1>

<table class="table table-bordered">
    

<?php foreach ($directions as $catId => $cat):?>
    <tr>
        <td>
            <?php echo $catId;?>
        </td>
        <td>
            <p><strong><?php echo $cat['name'];?></strong></p>
            
            <?php if ($cat['children']):?>
                <table class="table">
                    <tr>
                        <th></th>
                        <th></th>
                        <th>Родитель</th>
                    </tr>
                <?php foreach ($cat['children'] as $childId => $child):?>
                    <tr>
                        <td style="width:15%">
                            <?php echo $child['id'];?>
                        </td>
                        <td style="width:75%">
                            <?php echo $child['name'];?>
                            <div class="set-parent-result"></div>
                        </td>
                        <td style="width:10%">
                            <?php echo CHtml::textField('parent[' . $child['id'] . ']', $child['parentDirectionId'], ['class' => 'form-control change-direction-parent', 'data-id' => $child['id']]);?>
                            <?php //echo $child['parentDirectionId'];?>
                        </td>
                    </tr>
                <?php endforeach;?>
                </table>
            <?php endif;?>
        
        </td>
    </tr>
    

<?php endforeach;?>
</table>