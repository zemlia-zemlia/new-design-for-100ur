<?php
/* @var $this ContactController */
/* @var $data Contact */

switch ($data->leadStatus) {
    case Lead::LEAD_STATUS_DEFAULT:
        $statusClass = 'label-default';
        break;
    case Lead::LEAD_STATUS_SENT_CRM:
        $statusClass = 'label-primary';
        break;
    case Lead::LEAD_STATUS_NABRAK:
        $statusClass = 'label-warning';
        break;
    case Lead::LEAD_STATUS_BRAK:
        $statusClass = 'label-warning';
        break;
    case Lead::LEAD_STATUS_RETURN:
        $statusClass = 'label-info';
        break;
    case Lead::LEAD_STATUS_SENT:
        $statusClass = 'label-success';
        break;
    case Lead::LEAD_STATUS_DUPLICATE:
        $statusClass = 'label-warning';
        break;
    default:
        $statusClass = 'label-default';
}
?>
<div class="box">
    <div class="box-body">
        <div class="row" id="lead-<?php echo $data->id; ?>">
            <div class="col-md-10">
                <p>
                    <?php if ($data->townId): ?>
                        <span class="glyphicon glyphicon-map-marker"></span>
                        <?php echo CHtml::encode($data->town->name); ?> (<?php echo CHtml::encode($data->town->region->name); ?>)

                        <?php
                        $distanceFromCapital = $data->town->region->getRangeFromCenter($data->town->lat, $data->town->lng);
                        ?>

                        <?php if ($distanceFromCapital >= 0): ?>
                            <span class="label label-default"><abbr
                                        title="Расстояние от центра региона"><?php echo $distanceFromCapital; ?>
                            км.</abbr></span>
                        <?php endif; ?>
                    <?php endif; ?>


                    &nbsp;
                    <span class="glyphicon glyphicon-earphone"></span>
                    <?php echo CHtml::encode($data->phone); ?> &nbsp;

                    <span class="glyphicon glyphicon-user"></span>
                    <?php echo CHtml::link(CHtml::encode($data->name), ['/admin/lead/view', 'id' => $data->id]); ?>

                </p>

                <p class="small">
                    <?php echo nl2br(CHtml::encode($data->question)); ?>
                </p>


                <?php if ($data->brakReason): ?>
                    <p>
                        <strong>Причина отбраковки:</strong>
                        <?php echo CHtml::encode($data->getReasonName()); ?>
                        <br/>
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

            <div class="col-md-2">
                <small>
                        <span class="label <?php echo $statusClass; ?>">
                            <?php echo $data->getLeadStatusName(); ?>
                            <?php if ($data->campaign && $data->campaign->buyer): ?>
                                <?php echo CHtml::encode($data->campaign->buyer->name); ?>
                            <?php else: ?>
                                <?php if ($data->buyerId && $data->buyer): ?>
                                    <?php echo $data->buyer->getShortName(); ?>
                                <?php endif; ?>
                            <?php endif; ?>
                        </span>
                </small>
                &nbsp
                <small class="muted">
                    <span class="label label-default"><?php echo $data->getLeadTypeName(); ?></span>
                    &nbsp

                    <span>id:&nbsp;<?php echo $data->id; ?></span> &nbsp;

                    <?php if (Yii::app()->user->checkAccess(User::ROLE_ROOT) || User::ROLE_SECRETARY == Yii::app()->user->role): ?>
                        <span class="glyphicon glyphicon-log-in"></span>&nbsp;<?php echo $data->source->name; ?>
                    <?php endif; ?>
                    &nbsp

                    <span class="glyphicon glyphicon-calendar"></span>&nbsp<?php echo DateHelper::niceDate($data->question_date, false, false); ?>
                    &nbsp
                    <?php if (Yii::app()->user->checkAccess(User::ROLE_ROOT)): ?>
                        <?php if ($data->questionObject->ip): ?>
                            <?php echo 'IP:&nbsp;' . $data->questionObject->ip; ?>
                        <?php endif; ?>&nbsp;

                        <?php if ($data->questionObject->townIdByIP): ?>
                            <?php echo 'IPGeo: ' . $data->questionObject->townByIP->name; ?>
                        <?php endif; ?>
                    <?php endif; ?>
                </small>

                <?php if ($data->questionId): ?>
                    <p id="lead_<?php echo $data->id; ?>" class="small">
                        <?php echo CHtml::link($data->questionId, Yii::app()->createUrl('/admin/question/view', ['id' => $data->questionId])); ?>
                    </p>
                <?php endif; ?>

                <?php if (Lead::LEAD_STATUS_NABRAK == $data->leadStatus): ?>
                    <p>
                        <?php echo CHtml::link('В брак', '#', ['class' => 'btn btn-warning btn-xs btn-block lead-change-status', 'data-id' => $data->id, 'data-status' => Lead::LEAD_STATUS_BRAK, 'data-refund' => 1]); ?>
                    </p>
                    <p>
                        <?php echo CHtml::link('Возврат', '#', ['class' => 'btn btn-success btn-xs btn-block lead-change-status', 'data-id' => $data->id, 'data-status' => Lead::LEAD_STATUS_RETURN]); ?>
                    </p>
                    <div id="lead-status-message-<?php echo $data->id; ?>"></div>
                <?php endif; ?>

                <?php if (Lead::LEAD_STATUS_PREMODERATION == $data->leadStatus): ?>
                    <p>
                        <?php echo CHtml::link('В брак', '#', ['class' => 'btn btn-warning btn-xs btn-block lead-change-status', 'data-id' => $data->id, 'data-status' => Lead::LEAD_STATUS_BRAK, 'data-refund' => 1]); ?>
                    </p>
                    <p>
                        <?php echo CHtml::link('На продажу', '#', ['class' => 'btn btn-success btn-xs btn-block lead-change-status', 'data-id' => $data->id, 'data-status' => Lead::LEAD_STATUS_DEFAULT]); ?>
                    </p>
                    <div id="lead-status-message-<?php echo $data->id; ?>"></div>
                <?php endif; ?>

                <?php if (Lead::LEAD_STATUS_SENT == $data->leadStatus): ?>
                    <?php
                    $holdExpiration = (new DateTime($data->deliveryTime))->add(new DateInterval('P' . Yii::app()->params['leadHoldPeriodDays'] . 'D'));
                    $now = new DateTime();
                    if ($holdExpiration > $now) {
                        $daysLeftInHold = $holdExpiration->diff($now, true)->format('%a');
                        $hoursLeftInHold = $holdExpiration->diff($now, true)->format('%h');
                        echo "<span class='label label-danger'>" . $daysLeftInHold . 'д ' . $hoursLeftInHold . 'ч' . '</span>';
                    }
                    ?>
                <?php endif; ?>
            </div>

        </div>
    </div>
</div>
