<?php
$usersCount = 0;
?>

<div class="container-fluid">
    <?php foreach ($users as $user): ?>
        <?php ++$usersCount; ?>

        <div class="row row-yurist yurist-list-item">
            <div class="col-xs-1">
                <span class="badge"><?php echo $usersCount; ?></span>
            </div>

            <div class="col-xs-3 text-center">
                <div>
                    <a href="<?php echo Yii::app()->createUrl('user/view', ['id' => $user['id']]); ?>?from=top_yurist_widget"
                       rel="nofollow">
                        <img src="<?php echo User::USER_PHOTO_PATH . User::USER_PHOTO_THUMB_FOLDER . '/' . $user['avatar']; ?>"
                             alt="<?php echo CHtml::encode($user['name'] . ' ' . $user['lastName']); ?>"
                             class="img-responsive center-block gray-panel"/>
                    </a>
                </div>

            </div>
            <div class="col-xs-8">
                <div>
                    <a href="<?php echo Yii::app()->createUrl('user/view', ['id' => $user['id']]); ?>?from=top_yurist_widget"
                       rel="nofollow">
                        <?php echo CHtml::encode($user['name'] . ' ' . $user['lastName']); ?>
                    </a>
                    <div>
                    <span class="text-muted"><em>
                            <small>
                            <?php echo YuristSettings::getStatusNameByCode($user['yuristStatus']); ?>
                            </small>
                        </em>
                    </span>

                        <?php if ($user['townName']): ?>
                            <em class="text-muted">
                                <small>
                                    (<?php echo $user['townName']; ?>)
                                </small>
                            </em>
                        <?php endif; ?>

                    </div>
                </div>


                <?php if (isset($user['answersCounter'])): ?>
                    <div>
                        <small>
                            <?php echo $user['answersCounter'] . ' ' . NumbersHelper::numForms($user['answersCounter'], 'консультация', 'консультации', 'консультаций'); ?>
                        </small>
                    </div>
                <?php endif; ?>

                <?php if (floor((time() - strtotime($user['lastActivity'])) / 60) < 60): ?>
                    <div class="small"><span class="glyphicon glyphicon-flash"></span> <span class="text-success">Сейчас на сайте</span>
                    </div>
                <?php endif; ?>

            </div>


        </div> <!-- .row-yurist -->

    <?php endforeach; ?>
</div>
