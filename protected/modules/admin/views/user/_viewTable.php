    <tr>
        <td>
            <?php echo $data->id;?>
        </td>
        <td>
            <?php echo CHtml::link(CHtml::encode($data->name . ' ' . $data->name2 . ' ' . $data->lastName), array('view', 'id'=>$data->id)); ?>
            <?php if($data->active100==0):?>
            <span class="label label-default">неактивен</span>
            <?php endif;?>
            
            <?php if($data->role == User::ROLE_PARTNER):?>
            <span class='text-muted'><?php echo round($data->calculateWebmasterBalance(), 2);?> руб.</span>
            <?php endif;?>
        </td>
        <td>
           <?php echo $data->town->name;?>
        </td>
        <td>
            <?php echo CHtml::encode($data->email); ?>
        </td>
        <td>
            <?php echo CHtml::encode($data->phone); ?>
        </td>
        
        <?php if($data->role == User::ROLE_BUYER):?>
        <td class="text-center">
            <?php echo $data->campaignsCount;?>
        </td>
        <?php endif;?>
        
        <td>
            <?php if(Yii::app()->user->checkAccess(User::ROLE_ROOT)):?>
                <?php echo CHtml::link('Редактировать', Yii::app()->createUrl('/admin/user/update',array('id'=>$data->id)), array('class'=>'btn btn-xs btn-primary'));?>
            <?php endif;?>
        </td>
    </tr>
