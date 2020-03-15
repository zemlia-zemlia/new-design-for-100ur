<?php
/* @var $this QuestionController */

use App\helpers\DateHelper;

/* @var $data Question */
?>



<?php
switch ($data->status) {
    case Question::STATUS_NEW:
        $statusClass = '';
        break;
    case Question::STATUS_MODERATED:
        $statusClass = 'info';
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


        <tr class="<?php echo $statusClass; ?> box vert-margin40" id="question-<?php echo $data->id; ?>">
            <td>
                <?php if ($data->title): ?>
                    <h4 class='left-align'><?php echo CHtml::link(CHtml::encode($data->title), Yii::app()->createUrl('/admin/question/view', ['id' => $data->id])); ?></h4>
                <?php endif; ?>

                <p>
                    <?php echo CHtml::encode($data->questionText); ?>
                </p>

                <small>
                    <?php if (Yii::app()->user->checkAccess(User::ROLE_EDITOR)): ?>
                        <?php if ($data->createDate) {
    echo DateHelper::niceDate($data->createDate, false, false);
} ?>
                        <?php
                        if ($data->publishDate) {
                            echo "<span class='muted'>Опубликован " . DateHelper::niceDate($data->publishDate) . ' ' . CHtml::link(CHtml::encode($data->bublishUser->name . ' ' . $data->bublishUser->lastName), Yii::app()->createUrl('question/byPublisher', ['id' => $data->bublishUser->id])) . '</span>';
                        }
                        ?>
                        &nbsp;
                    <?php endif; ?>

                    <b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
                    <?php echo CHtml::link(CHtml::encode($data->id), Yii::app()->createUrl('/admin/question/view', ['id' => $data->id])); ?>
                    &nbsp;
                    <?php if ($data->town): ?>
                        <span class="glyphicon glyphicon-map-marker"></span>&nbsp;<?php echo CHtml::encode($data->town->name . ' (' . $data->town->region->name . ')'); ?>
                    <?php endif; ?>
                    &nbsp;
                    <?php if (Yii::app()->user->checkAccess(User::ROLE_ROOT)): ?>
                        <?php if ($data->ip): ?>
                            <?php echo 'IP: ' . $data->ip; ?> &nbsp;
                        <?php endif; ?>
                        <?php if ($data->townIdByIP): ?>
                            <?php echo 'Город по IP адресу: ' . $data->townByIP->name; ?> &nbsp;
                        <?php endif; ?>
                    <?php endif; ?>

                    <?php if ($data->author instanceof User): ?>
                        <span class="glyphicon glyphicon-user"></span>&nbsp;<?php echo CHtml::link(CHtml::encode($data->authorName), Yii::app()->createUrl('admin/user/view', ['id' => $data->authorId])); ?>
                    <?php elseif ($data->authorName): ?>
                        <span class="glyphicon glyphicon-user"></span>&nbsp;<?php echo CHtml::encode($data->authorName); ?>
                    <?php endif; ?>

                    &nbsp;

                    <?php if (User::ROLE_EDITOR == Yii::app()->user->role || User::ROLE_ROOT == Yii::app()->user->role): ?>
                        Ответов: <?php echo $data->answersCount; ?>
                    <?php endif; ?>
                    &nbsp;
                    <?php echo $data->getQuestionStatusName(); ?>
                    <?php if (Question::STATUS_NEW == $data->status && $data->email): ?>
                        &nbsp;&nbsp;
                        Email: <?php echo CHtml::encode($data->email); ?>
                    <?php endif; ?>
                </small>

                <?php if (1 == $data->payed): ?>
                    <div>
                        <span class="label label-info">VIP</span> <?php echo MoneyFormat::rubles($data->price); ?> руб.
                    </div>
                <?php endif; ?>
            </td>

            <?php if (Yii::app()->user->checkAccess(User::ROLE_ROOT)): ?>
                <td>
                    <?php if (sizeof($data->categories)): ?>
                        <small>
                            <?php if (!$hideCategory): ?>
                                <?php foreach ($data->categories as $category): ?>
                                    <?php echo CHtml::link($category->name, Yii::app()->createUrl('/admin/questionCategory/view', ['id' => $category->id])); ?>
                                    <br/>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </small>
                    <?php else: ?>
                        <?php if (true === $nocat): ?>
                            <small>
                                <?php foreach ($allDirections as $directionId => $directionName): ?>
                                    <?php echo CHtml::link($directionName, '#', ['class' => 'set-category-link', 'data-category' => $directionId, 'data-question' => $data->id]); ?>
                                    <br/>

                                <?php endforeach; ?>
                            </small>
                        <?php endif; ?>
                    <?php endif; ?>
                </td>
            <?php endif; ?>
            <td>
                <?php if (Yii::app()->user->checkAccess(User::ROLE_ROOT) || Yii::app()->user->checkAccess(User::ROLE_EDITOR)): ?>

                    <?php echo CHtml::link('Редактировать', Yii::app()->createUrl('/admin/question/update', ['id' => $data->id]), ['class' => 'btn btn-primary btn-xs btn-block']); ?>
                    <?php echo CHtml::ajaxLink('В спам', Yii::app()->createUrl('/admin/question/toSpam'), ['data' => 'id=' . $data->id, 'type' => 'POST', 'success' => 'onSpamQuestion'], ['class' => 'btn btn-warning btn-xs btn-block']); ?>

                    <?php if (Yii::app()->user->checkAccess(User::ROLE_ROOT)): ?>
                        <?php echo CHtml::link('Удалить', Yii::app()->createUrl('/admin/question/delete', ['id' => $data->id]), ['class' => 'btn btn-danger btn-xs btn-block']); ?>
                    <?php endif; ?>

                <?php endif; ?>
            </td>
        </tr>


        <?php //endif;?>

