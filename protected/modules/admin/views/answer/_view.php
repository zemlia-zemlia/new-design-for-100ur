<?php
/* @var $this QuestionController */
/* @var $data Question */
?>

<?php
switch ($data->status) {
    case Question::STATUS_NEW:
        $statusClass = '';
        break;
    case Question::STATUS_PUBLISHED:
        $statusClass = 'success';
        break;
    case Question::STATUS_SPAM:
        $statusClass = 'danger';
        break;
    default:
        $statusClass = '';
}
?>
<div class="<?php echo $statusClass; ?>" id="answer-<?php echo $data->id; ?>">

    <div class="box">
        <div class="box-body">
            <div class="row <?php echo $statusClass; ?>" id="answer-<?php echo $data->id; ?>">
                <div class="col-md-8">
                    <p>
                        <strong>Вопрос:</strong>
                        <?php echo $data->question ? CHtml::encode(StringHelper::cutString($data->question->questionText, 1000)) : ''; ?>
                    </p>

                    <p>
                        <strong>Ответ
                            <?php if ($data->isFast()): ?>
                                <span class="text-success"><span
                                            class="glyphicon glyphicon-flash"></span> Быстрый</span>
                            <?php endif; ?>
                            :</strong>
                        <?php echo CHtml::encode($data->answerText); ?>
                    </p>
                </div>
                <div class="col-md-2">
                    <p>
                        <strong>Дата ответа:</strong>
                        <?php if (Yii::app()->user->checkAccess(User::ROLE_EDITOR)): ?>
                            <?php if ($data->datetime) {
    echo DateHelper::niceDate($data->datetime, false, false);
} ?>
                            &nbsp;
                        <?php endif; ?>
                    </p>
                    <p>
                        <strong>ID вопроса:</strong>
                        <?php echo CHtml::link(CHtml::encode($data->questionId), Yii::app()->createUrl('/admin/question/view', ['id' => $data->questionId])); ?>
                    </p>
                    <p>
                        <strong>ID ответа:</strong>
                        <?php echo CHtml::link(CHtml::encode($data->id), Yii::app()->createUrl('/admin/answer/view', ['id' => $data->id])); ?>
                    </p>
                    <p>
                        <?php if ($data->author): ?>
                            <span class="glyphicon glyphicon-user"></span>&nbsp;<?php echo CHtml::encode($data->author->lastName . ' ' . $data->author->name); ?>
                        <?php endif; ?>
                    </p>
                    <p>
                        <?php echo $data->getAnswerStatusName(); ?>
                    </p>
                </div>

                <?php if (Yii::app()->user->checkAccess(User::ROLE_ROOT)): ?>
                    <div class="col-md-2">
                        <?php if (Yii::app()->user->checkAccess(User::ROLE_ROOT) || Yii::app()->user->checkAccess(User::ROLE_EDITOR)): ?>

                            <?php echo CHtml::link('Редактировать', Yii::app()->createUrl('/admin/answer/update', ['id' => $data->id]), ['class' => 'btn btn-primary btn-xs btn-block']); ?>

                            <?php if (Answer::STATUS_PUBLISHED != $data->status): ?>
                                <?php echo CHtml::ajaxLink('Одобрить', Yii::app()->createUrl('/admin/answer/publish'), ['data' => 'id=' . $data->id, 'type' => 'POST', 'success' => 'onPublishAnswer'], ['class' => 'btn btn-success btn-xs btn-block']); ?>
                            <?php endif; ?>

                            <?php if (Answer::STATUS_PUBLISHED != $data->status && !$data->transactionId): ?>
                                <?php echo CHtml::ajaxLink('Одобрить и оплатить', Yii::app()->createUrl('/admin/answer/payBonus'), ['data' => 'id=' . $data->id, 'type' => 'POST', 'success' => 'onPayBonus'], ['class' => 'btn btn-success btn-xs btn-block']); ?>
                            <?php endif; ?>

                            <?php if (Answer::STATUS_SPAM != $data->status): ?>
                                <?php echo CHtml::ajaxLink('В спам', Yii::app()->createUrl('/admin/answer/toSpam'), ['data' => 'id=' . $data->id, 'type' => 'POST', 'success' => 'onSpamAnswer'], ['class' => 'btn btn-warning btn-xs btn-block']); ?>
                            <?php endif; ?>

                            <?php if (Yii::app()->user->checkAccess(User::ROLE_ROOT)): ?>
                                <?php echo CHtml::link('Удалить', Yii::app()->createUrl('/admin/answer/delete', ['id' => $data->id]), ['class' => 'btn btn-danger btn-xs btn-block']); ?>
                            <?php endif; ?>

                            <?php if ($data->transaction instanceof TransactionCampaign): ?>
                                Бонус <?php echo MoneyFormat::rubles($data->transaction->sum); ?>
                            <?php endif; ?>

                        <?php endif; ?>
                    </div>

                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
