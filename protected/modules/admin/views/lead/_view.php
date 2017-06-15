<?php
/* @var $this ContactController */
/* @var $data Contact */

switch ($data->leadStatus) {
    case Lead100::LEAD_STATUS_DEFAULT:
        $statusClass = 'label-default';
        break;
    case Lead100::LEAD_STATUS_SENT_CRM:
        $statusClass = 'label-primary';
        break;
    case Lead100::LEAD_STATUS_NABRAK:
        $statusClass = 'label-warning';
        break;
    case Lead100::LEAD_STATUS_BRAK:
        $statusClass = 'label-warning';
        break;
    case Lead100::LEAD_STATUS_RETURN:
        $statusClass = 'label-info';
        break;
    case Lead100::LEAD_STATUS_SENT:
        $statusClass = 'label-success';
        break;
    case Lead100::LEAD_STATUS_DUPLICATE:
        $statusClass = 'label-warning';
        break;
    default :
        $statusClass = 'label-default';
}
?>

<tr id="lead-<?php echo $data->id;?>" >
    <td class="warning" style="min-width: 120px;">
        
        <small class="muted" > 
            
            <span>id:&nbsp;<?php echo $data->id;?></span> &nbsp;
            <br />
            <span class="glyphicon glyphicon-calendar"></span>&nbsp;<?php echo CustomFuncs::niceDate($data->question_date, false, false); ?>&nbsp;&nbsp;
            <br />
            <?php if(Yii::app()->user->checkAccess(User::ROLE_ROOT) || Yii::app()->user->role == User::ROLE_SECRETARY):?>
                <span class="glyphicon glyphicon-log-in"></span>&nbsp;<?php echo $data->source->name; ?>       
            <?php endif;?>
			<br />
            <span class="label <?php echo $statusClass;?>">    
            <?php echo $data->getLeadStatusName();?>
            </span>
            <br /> 
            <span class="label label-default"><?php echo $data->getLeadTypeName();?></span>
			<br /> 
			<?php if(Yii::app()->user->checkAccess(User::ROLE_ROOT)):?>
				<?php if($data->questionObject->ip):?>
					<?php echo "IP: " . $data->questionObject->ip;?>
				<?php endif;?>
				<br /> 
				<?php if($data->questionObject->townIdByIP):?>
					<?php echo "IPGeo: " . $data->questionObject->townByIP->name;?>
				<?php endif;?>
			<?php endif;?>
        </small>
    </td>
    <td class="success" >

            
		<p style="border-bottom: #ded9d9 1px solid; ">
            <?php if($data->townId):?>
                <span class="glyphicon glyphicon-map-marker"></span>
                <?php echo CHtml::encode($data->town->name); ?> (<?php echo CHtml::encode($data->town->region->name); ?>)
                
                <?php 
                    $distanceFromCapital = $data->town->region->getRangeFromCenter($data->town->lat, $data->town->lng);
                ?>
                <?php if($distanceFromCapital >=0):?>
                <span class="label label-default"><abbr title="Расстояние от центра региона"><?php echo $distanceFromCapital;?>  км.</abbr></span>
                <?php endif;?>
                

            <?php endif;?>
            &nbsp;
            
            <?php if($data->phone && !(Yii::app()->user->role == User::ROLE_JURIST && $data->employeeId != Yii::app()->user->id)):?>
                <span class="glyphicon glyphicon-earphone"></span>
                <?php echo CHtml::encode($data->phone); ?> &nbsp;
            <?php endif;?>
            <?php if($data->email):?>
                <span class="glyphicon glyphicon-envelope"></span>
                <?php echo CHtml::encode($data->email); ?>  &nbsp;    
            <?php endif;?>
            
            <span class="glyphicon glyphicon-user"></span>    
            <?php echo CHtml::link(CHtml::encode($data->name), array('view', 'id'=>$data->id)); ?> <br />
        </p>
		
		<p >
            <?php echo nl2br(CHtml::encode($data->question)); ?>
        </p>
        
        <?php if($data->brakReason):?>
        <p>
            <strong>Причина отбраковки:</strong>
            <?php echo CHtml::encode($data->getReasonName());?>
            <br />
            <strong>Комментарий отбраковки:</strong>
            <?php echo CHtml::encode($data->brakComment);?>
        </p>
        <?php endif;?>
        	
		
    </td>
    
    <td class="success">
        <p id="lead_<?php echo $data->id;?>" class="small">
            <?php if($data->questionId):?>
                <?php echo CHtml::link($data->questionId, Yii::app()->createUrl('/admin/question/view', array('id'=>$data->questionId)));?>
            <?php else:?>
                <?php if($data->sourceId!=3):?>
                    <?php echo CHtml::ajaxLink('В вопрос', Yii::app()->createUrl('/admin/lead/toQuestion', array('id'=>$data->id)), array('type'=>'POST', 'success'=>'LeadToQuestionAjax'), array('class'=>'btn btn-primary btn-xs btn-block'));?>
                <?php endif;?>
            <?php endif;?>
        </p>
    
        <?php if($data->leadStatus == Lead100::LEAD_STATUS_NABRAK):?>
            <?php echo CHtml::link('В брак', '#', array('class'=>'btn btn-warning btn-xs btn-block lead-change-status', 'data-id'=>$data->id, 'data-status'=>Lead100::LEAD_STATUS_BRAK));?>
            <?php echo CHtml::link('Возврат', '#', array('class'=>'btn btn-success btn-xs btn-block lead-change-status', 'data-id'=>$data->id, 'data-status'=>Lead100::LEAD_STATUS_RETURN));?>
            <div id="lead-status-message-<?php echo $data->id;?>"></div>
        <?php endif;?>
    </td>
</tr>
