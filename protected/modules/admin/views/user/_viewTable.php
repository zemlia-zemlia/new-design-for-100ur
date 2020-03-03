<tr>
    <td>
        <?php echo $data->id; ?>
    </td>
    <td>
        <?php echo CHtml::link(CHtml::encode($data->name . ' ' . $data->name2 . ' ' . $data->lastName), ['view', 'id' => $data->id]); ?>
        <?php if (0 == $data->active100): ?>
            <span class="label label-default">неактивен</span>
        <?php endif; ?>

        <?php if (User::ROLE_PARTNER == $data->role): ?>
            <span class='text-muted'><?php echo MoneyFormat::rubles($data->calculateWebmasterBalance()); ?> руб.</span>
        <?php endif; ?>

        <?php if (User::ROLE_JURIST == $data->role): ?>
            <?php echo $data->settings->getStatusName(); ?>
            <?php if ($data->settings->isVerified): ?>
                <span class="label label-success">подтвержден</span>
            <?php else: ?>
                <span class="label label-warning">не подтвержден</span>
            <?php endif; ?>
        <?php endif; ?>

    </td>
    <?php if (User::ROLE_JURIST == $data->role): ?>
        <td>
            <?php echo DateHelper::niceDate($data->lastActivity, true, false); ?>
        </td>
    <?php endif; ?>
    <td>
        <?php echo $data->town->name; ?>
    </td>
    <td>
        <?php echo CHtml::encode($data->email); ?>
    </td>
    <td>
        <?php echo CHtml::encode($data->phone); ?>
    </td>

    <?php if (User::ROLE_BUYER == $data->role): ?>
        <td class="text-center">
            <?php echo $data->campaignsCount; ?>
        </td>
    <?php endif; ?>

    <td>
        <?php if (Yii::app()->user->checkAccess(User::ROLE_ROOT)): ?>
            <?php echo CHtml::link('Редактировать', Yii::app()->createUrl('/admin/user/update', ['id' => $data->id]), ['class' => 'btn btn-xs btn-primary']); ?>
        <?php endif; ?>
    </td>
</tr>
