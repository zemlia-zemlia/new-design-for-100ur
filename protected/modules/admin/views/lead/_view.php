<?php
/* @var $this ContactController */
/* @var $data Contact */
?>

<tr>
    <td>
        <p class="small">
            <?php echo nl2br(mb_substr(CHtml::encode($data->question),0,300,'utf-8')); ?>
            <?php if(strlen($data->question)>300) echo "...";?>
        </p>

        <span class="muted">id:&nbsp;<?php echo $data->id;?></span> &nbsp;
        
        <span class="glyphicon glyphicon-calendar"></span>&nbsp;<?php echo CustomFuncs::niceDate($data->question_date, false, false); ?>&nbsp;&nbsp;

        <?php if(Yii::app()->user->checkAccess(User::ROLE_ROOT) || Yii::app()->user->role == User::ROLE_SECRETARY):?>
            <span class="glyphicon glyphicon-log-in"></span>&nbsp;<?php echo $data->source->name; ?> &nbsp;&nbsp;       
        <?php endif;?>
            
        <?php echo CHtml::link(CHtml::encode($data->name), array('view', 'id'=>$data->id)); ?> 
    </td>
    <td>
 <p> <small>
            <?php if($data->phone && !(Yii::app()->user->role == User::ROLE_JURIST && $data->employeeId != Yii::app()->user->id)):?>
                <?php echo CHtml::encode($data->phone); ?><br />
            <?php endif;?>
            <?php if($data->email):?>
                <?php echo CHtml::encode($data->email); ?><br />        
            <?php endif;?>
            <?php if($data->townId):?>
                <span class="glyphicon glyphicon-map-marker"></span>
                    <?php echo CHtml::encode($data->town->name); ?> (<?php echo CHtml::encode($data->town->ocrug); ?>)<br />
            <?php endif;?>
        </p>
        
    </td>
    <td>        
        <?php echo $data->getLeadStatusName();?><br />
                
    </td>
    <td>
        <p id="lead_<?php echo $data->id;?>" class="small">
            <?php if($data->questionId):?>
                <?php echo CHtml::link($data->questionId, Yii::app()->createUrl('/admin/question/view', array('id'=>$data->questionId)));?>
            <?php else:?>
                <?php echo CHtml::ajaxLink('В вопрос', Yii::app()->createUrl('/admin/lead/toQuestion', array('id'=>$data->id)), array('type'=>'POST', 'success'=>'LeadToQuestionAjax'), array('class'=>'btn btn-primary btn-xs'));?>
            <?php endif;?>
        </p>  </small>
    </td>
</tr>
