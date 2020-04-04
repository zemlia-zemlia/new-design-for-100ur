<?php
/* @var $this ContactController */

use App\helpers\DateHelper;
use App\models\Lead;
use App\models\User;

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
            <div class="col-md-2" style="min-width: 120px;">

                <small class="muted">

            <span class="glyphicon glyphicon-calendar"></span>&nbsp;<?php echo DateHelper::niceDate($data->question_date, false, false); ?>&nbsp;&nbsp;
            <br />
                <span class="glyphicon glyphicon-log-in"></span>&nbsp;<?php echo CHtml::encode($data->source->name); ?>       
            <br />
            <span class="label <?php echo $statusClass; ?>">    
                <?php echo $data->getLeadStatusName(); ?>
            </span>
                    &nbsp
                    <?php if ($data->buyPrice > 0 && Lead::LEAD_STATUS_BRAK != $data->leadStatus): ?>
                        <?php echo MoneyFormat::rubles($data->buyPrice); ?> руб.
                    <?php endif; ?>
                </small>
            </div>
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
                                        title="Расстояние от центра региона"><?php echo $distanceFromCapital; ?>  км.</abbr></span>
                        <?php endif; ?>


                    <?php endif; ?>

                    <?php if ($data->phone && !(User::ROLE_JURIST == Yii::app()->user->role && $data->employeeId != Yii::app()->user->id)): ?>
                        <span class="glyphicon glyphicon-earphone"></span>
                        <?php echo CHtml::encode($data->phone); ?> &nbsp
                    <?php endif; ?>
                    <?php if ($data->email): ?>
                        <span class="glyphicon glyphicon-envelope"></span>
                        <?php echo CHtml::encode($data->email); ?>  &nbsp
                    <?php endif; ?>

                    <span class="glyphicon glyphicon-user"></span>
                    <?php echo CHtml::link(CHtml::encode($data->name), ['/webmaster/lead/view', 'id' => $data->id]); ?>
                </p>

                <p>
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


            </div>
        </div>
    </div>
</div>
