<?php
/* @var $this UserStatusRequestController */
/* @var $data UserStatusRequest */
?>

<tr id="request-id-<?php echo $data->id;?>">

    <td>
        <?php echo CHtml::encode($data->user->name . ' ' . $data->user->name2 . ' ' . $data->user->lastName);?>
        <br />
        id: <?php echo CHtml::link($data->user->id, Yii::app()->createUrl('admin/user/view', array('id' => $data->user->id)));?>
        <br />
        Роль: <?php echo $data->user->getRoleName();?>
    </td>
    <td>
        <p>
        <?php 
            $statuses = YuristSettings::getStatusesArray();
            echo $statuses[$data->status];
        ?>
        </p>
        <p class="text-muted">
            <?php echo ($data->getVerificationStatusName());?>
        </p>
    </td>
    
    <td>
        <p> 
		<small>
            <?php if($data->education) echo "Образование: " . CHtml::encode($data->education); ?> <br/>
            <?php if($data->vuz) echo "ВУЗ: " . CHtml::encode($data->vuz); ?> <br/>
            <?php if($data->facultet) echo "Факультет: " . CHtml::encode($data->facultet); ?> <br/>
            <?php if($data->facultet) echo "Год выпуска: " . CHtml::encode($data->educationYear); ?> <br/>
            <?php if($data->facultet) echo "Город ВУЗа: " . CHtml::encode($data->vuzTown->name); ?> <br/>
 
        </small> 
		</p>
        <div class="request-status-message"></div>
    </td>
	
	<td>
	<p> 	
	<small>
			<?php if($data->advOrganisation) echo "Членство в адвокатском объединении: " . CHtml::encode($data->advOrganisation); ?> <br/>
            <?php if($data->advNumber) echo "Номер в реестре адвокатов: " . CHtml::encode($data->advNumber); ?> <br/>
            <?php if($data->position) echo "Должность: " . CHtml::encode($data->position); ?>
	</small>
	</p> 	
	</td>
	
	<td>
			<?php if($data->userFile) echo CHtml::link("Скан документа", UserFile::USER_FILES_FOLDER . '/' . $data->userFile->name, array('target'=>'_blank')); ?>
    </td>
	
    <td class="request-control-wrapper">
        <?php if($data->isVerified == UserStatusRequest::STATUS_NEW):?>
            <?php echo CHtml::link("Одобрить", "#", array('class'=>'btn btn-success btn-xs btn-block change-request-status', 'data-id'=>$data->id, 'data-action'=>'accept'));?>
            <?php echo CHtml::link("Отклонить", "#", array('class'=>'btn btn-danger btn-xs btn-block change-request-status', 'data-id'=>$data->id, 'data-action'=>'decline'));?>
            <div class="request-comment-wrapper">
                <form>
                    <textarea class="form-control" rows="3" placeholder="Причина отказа" name="comment"></textarea>
                    <input type="hidden" name="id" value="<?php echo $data->id;?>" />
                    <input type="button" class="btn btn-primary btn-block request-decline-button" value="Отклонить" />
                </form>
            </div>
        <?php endif;?>
    </td>
</tr>