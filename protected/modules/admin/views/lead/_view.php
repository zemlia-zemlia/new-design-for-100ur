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

<div class="row leads-row" id="lead-<?php echo $data->id; ?>" >
    <div class="col-sm-10">
        <p>


            <?php if ($data->townId): ?>
                <span class="glyphicon glyphicon-map-marker"></span>
                <?php echo CHtml::encode($data->town->name); ?> (<?php echo CHtml::encode($data->town->region->name); ?>)

                <?php
                $distanceFromCapital = $data->town->region->getRangeFromCenter($data->town->lat, $data->town->lng);
                ?>
                <?php if ($distanceFromCapital >= 0): ?>
                    <span class="label label-default"><abbr title="Расстояние от центра региона"><?php echo $distanceFromCapital; ?>  км.</abbr></span>
                <?php endif; ?>
            <?php endif; ?>

            <small>			
                <span class="label <?php echo $statusClass; ?>">    
                    <?php echo $data->getLeadStatusName(); ?>
                    <?php if ($data->campaign && $data->campaign->buyer): ?>
                        <?php echo CHtml::encode($data->campaign->buyer->name); ?> 
                    <?php endif; ?>
                </span>
            </small>			
            &nbsp;
            <span class="glyphicon glyphicon-earphone"></span>
            <?php echo CHtml::encode($data->phone); ?> &nbsp;

            <span class="glyphicon glyphicon-user"></span>    
            <?php echo CHtml::link(CHtml::encode($data->name), array('/admin/lead/view', 'id' => $data->id)); ?> 

        </p>

        <p class="small">
            <?php echo nl2br(CHtml::encode($data->question)); ?>
        </p>


        <?php if ($data->brakReason): ?>
            <p>
                <strong>Причина отбраковки:</strong>
                <?php echo CHtml::encode($data->getReasonName()); ?>
                <br />
                <strong>Комментарий отбраковки:</strong>
                <?php echo CHtml::encode($data->brakComment); ?>
            </p>
        <?php endif; ?>

        <?php if (sizeof($data->categories)): ?>  
            Категории:             
            <?php foreach ($data->categories as $cat): ?>
                <?php echo $cat->name; ?>&nbsp; 
            <?php endforeach; ?>
        <?php endif; ?>	


    </div>

    <div class="col-sm-2">
        <small>
            <?php if (Yii::app()->user->checkAccess(User::ROLE_ROOT) || Yii::app()->user->role == User::ROLE_SECRETARY): ?>
                <span class="glyphicon glyphicon-log-in"></span>&nbsp;<?php echo $data->source->name; ?>       
            <?php endif; ?></small><br/>

        <small class="muted" > 


            <span>id:&nbsp;<?php echo $data->id; ?></span> &nbsp;<br/>

            <span class="glyphicon glyphicon-calendar"></span>&nbsp;<?php echo CustomFuncs::niceDate($data->question_date, false, false); ?>&nbsp;<br/>

            <span class="label label-default"><?php echo $data->getLeadTypeName(); ?></span> <br/>

            <?php if (Yii::app()->user->checkAccess(User::ROLE_ROOT)): ?>
                <?php if ($data->questionObject->ip): ?>
                    <?php echo "IP:&nbsp;" . $data->questionObject->ip; ?>
                <?php endif; ?>&nbsp;<br/>

                <?php if ($data->questionObject->townIdByIP): ?>
                    <?php echo "IPGeo: " . $data->questionObject->townByIP->name; ?>
                <?php endif; ?>
            <?php endif; ?>
        </small>

        <p id="lead_<?php echo $data->id; ?>" class="small">
            <?php if ($data->questionId): ?>
                <?php echo CHtml::link($data->questionId, Yii::app()->createUrl('/admin/question/view', array('id' => $data->questionId))); ?>
            <?php else: ?>
                <?php if ($data->sourceId != 3): ?>
             <!--       <?php echo CHtml::ajaxLink('В вопрос', Yii::app()->createUrl('/admin/lead/toQuestion', array('id' => $data->id)), array('type' => 'POST', 'success' => 'LeadToQuestionAjax'), array('class' => 'btn btn-default btn-xs btn-block')); ?> -->
                <?php endif; ?>
            <?php endif; ?>

            <?php if ($data->leadStatus == Lead100::LEAD_STATUS_NABRAK): ?>
                <p>
                    <?php echo CHtml::link('В брак', '#', array('class' => 'btn btn-warning btn-xs btn-block lead-change-status', 'data-id' => $data->id, 'data-status' => Lead100::LEAD_STATUS_BRAK)); ?>
                </p>
                <p>
                    <?php echo CHtml::link('Возврат', '#', array('class' => 'btn btn-success btn-xs btn-block lead-change-status', 'data-id' => $data->id, 'data-status' => Lead100::LEAD_STATUS_RETURN)); ?>
                </p>	
                <div id="lead-status-message-<?php echo $data->id; ?>"></div>
            <?php endif; ?>
                
            <?php if ($data->leadStatus == Lead100::LEAD_STATUS_PREMODERATION): ?>
                <p>
                    <?php echo CHtml::link('В брак', '#', array('class' => 'btn btn-warning btn-xs btn-block lead-change-status', 'data-id' => $data->id, 'data-status' => Lead100::LEAD_STATUS_BRAK)); ?>
                </p>
                <p>
                    <?php echo CHtml::link('На продажу', '#', array('class' => 'btn btn-success btn-xs btn-block lead-change-status', 'data-id' => $data->id, 'data-status' => Lead100::LEAD_STATUS_DEFAULT)); ?>
                </p>	
                <div id="lead-status-message-<?php echo $data->id; ?>"></div>
            <?php endif; ?>
        </p>
    </div>
</div>
