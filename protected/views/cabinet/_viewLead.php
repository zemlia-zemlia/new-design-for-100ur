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
    
}
?>

<tr>
    <td>
        <p>
            <?php echo nl2br(mb_substr(CHtml::encode($data->question),0,300,'utf-8')); ?>
            <?php if(strlen($data->question)>300) echo "...";?>
        </p>

        <small class="muted">
        <span>id:&nbsp;<?php echo $data->id;?></span> &nbsp;
        
        <span class="glyphicon glyphicon-calendar"></span>&nbsp;<?php echo CustomFuncs::niceDate($data->question_date, false, false); ?>&nbsp;&nbsp;

        <?php if(Yii::app()->user->checkAccess(User::ROLE_ROOT) || Yii::app()->user->role == User::ROLE_SECRETARY):?>
            <span class="glyphicon glyphicon-log-in"></span>&nbsp;<?php echo $data->source->name; ?> &nbsp;&nbsp;       
        <?php endif;?>
          
            
        <span class="label <?php echo $statusClass;?>">    
        <?php echo $data->getLeadStatusName();?></span>
            <br />
                      
        
            <?php if($data->townId):?>
                <span class="glyphicon glyphicon-map-marker"></span>
                <?php echo CHtml::encode($data->town->name); ?> (<?php echo CHtml::encode($data->town->ocrug); ?>)
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
            <?php echo CHtml::encode($data->name)?> <br />
        </small>
    </td>
    
    <td>
        
        <?php echo CHtml::link("Просмотр", array('viewLead', 'id'=>$data->id), array('class'=>'btn btn-block btn-primary btn-sm')); ?>
        
        <?php 
            $leadTimestamp = strtotime($data->question_date);
            $now = time();
        ?>
        
        <?php if(($data->leadStatus == Lead100::LEAD_STATUS_SENT || $data->leadStatus == Lead100::LEAD_STATUS_SENT_CRM) && ($now - $leadTimestamp)<86400*4):?>
            <?php echo CHtml::link('На отбраковку', '#', array('class'=>'btn btn-block btn-default brak-lead btn-sm', 'data-id'=>$data->id));?>
            <div class="brak-lead-message" data-id="<?php echo $data->id;?>"></div>
            <form id="lead-<?php echo $data->id;?>" data-id="<?php echo $data->id;?>" class="form-inline form-brak-lead">
                <div class="form-group">
                     <?php echo CHtml::activeDropDownList(Lead100::model(),'brakReason', Lead100::getBrakReasonsArray(),array('class'=>'form-control'));?>
                </div>

                <a href="#" class="btn btn-primary btn-sm submit-brak-lead">Забраковать</a>
                <a href="#" class="submit-brak-close">Отмена</a>
            </form>
        <?php endif;?>
    </td>
</tr>
