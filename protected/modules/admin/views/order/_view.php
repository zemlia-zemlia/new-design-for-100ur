<?php
// раскрашиваем бейджи статусов
use App\helpers\DateHelper;

switch ($data->status) {
    case Order::STATUS_NEW:
        $statusClass = 'label-default';
        break;
    case Order::STATUS_CONFIRMED:
        $statusClass = 'label-warning';
        break;
    case Order::STATUS_JURIST_SELECTED:
        $statusClass = 'label-default';
        break;
    case Order::STATUS_JURIST_CONFIRMED:
        $statusClass = 'label-default';
        break;
    case Order::STATUS_DONE:
        $statusClass = 'label-info';
        break;
    case Order::STATUS_REWORK:
        $statusClass = 'label-info';
        break;
    case Order::STATUS_CLOSED:
        $statusClass = 'label-success';
        break;
    case Order::STATUS_ARCHIVE:
        $statusClass = 'label-default';
        break;
}
?>
<tr>
    <td>
        <?php echo CHtml::link($data->id, Yii::app()->createUrl('admin/order/view', ['id' => $data->id])); ?>
        <br/>
        <span class="label <?php echo $statusClass; ?>">
                <?php echo $data->getStatusName(); ?>
            </span>
    </td>
    <td>
        <?php echo $data->author->townName; ?>
    </td>

    <td>
        <?php echo DateHelper::niceDate($data->createDate, false, false); ?>
    </td>
    <td>
        <?php echo $data->docType->getClassName(); ?>.
        <?php echo $data->docType->name; ?>
    </td>
    <td>
        <?php echo ($data->jurist) ? $data->jurist->getShortName() : 'нет'; ?>
    </td>
    <td>
        <p>
            <span class="glyphicon glyphicon-comment"></span> <?php echo $data->responsesCount; ?>
        </p>
    </td>

</tr>
