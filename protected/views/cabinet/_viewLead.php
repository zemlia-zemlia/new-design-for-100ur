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
       
        <span class="glyphicon glyphicon-calendar"></span>&nbsp;<?php echo CustomFuncs::niceDate($data->deliveryTime, false, false); ?>&nbsp;&nbsp;

        <?php if(Yii::app()->user->checkAccess(User::ROLE_ROOT) || Yii::app()->user->role == User::ROLE_SECRETARY):?>
            <span class="glyphicon glyphicon-log-in"></span>&nbsp;<?php echo $data->source->name; ?> &nbsp;&nbsp;       
        <?php endif;?>
          

            
                      
        
            <?php if($data->townId):?>
                <span class="glyphicon glyphicon-map-marker"></span>
                <?php echo CHtml::encode($data->town->name); ?> (<?php echo CHtml::encode($data->town->region->name); ?>)
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
            <?php echo CHtml::encode($data->name)?> 
			
			            
			<span class="label <?php echo $statusClass;?>">    
			<?php echo $data->getLeadStatusName();?></span>
            
            <?php if($data->leadStatus == Lead100::LEAD_STATUS_NABRAK && $data->brakComment):?>
            <p>
                <strong>Комментарий отбраковки:</strong>
                <?php echo CHtml::encode($data->brakComment);?>
            </p>
            <?php endif;?>
        </small>
    </td>
    
    <td>
        
        <?php echo CHtml::link("Просмотр", array('viewLead', 'id'=>$data->id), array('class'=>'btn btn-block btn-primary btn-sm')); ?>
        
        <?php 
            $leadTimestamp = strtotime($data->question_date);
            $now = time();
        ?>
        
        <?php if(($data->leadStatus == Lead100::LEAD_STATUS_SENT || $data->leadStatus == Lead100::LEAD_STATUS_SENT_CRM) && ($now - $leadTimestamp)<86400*4 && $data->campaign->brakPercent > 0):?>
            <?php echo CHtml::link('На отбраковку', Yii::app()->createUrl('site/brakLead',array('code'=>$data->secretCode)), array('class'=>'btn btn-block btn-default btn-sm', 'target'=>'_blank', 'data-id'=>$data->id));?>

        <?php endif;?>
    </td>
</tr>
