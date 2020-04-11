<?php

use App\helpers\NumbersHelper;
use App\models\User;
use App\models\YuristSettings;

$usersCount = 0;
?>

<div class="container-fluid">
    <?php foreach ($usersData as $userData): ?>
        <?php ++$usersCount; ?>
        <?php
        /** @var User $user */
        $user = $userData['user'];
        ?>

        <div class="row row-yurist yurist-list-item">
            <div class="col-xs-1">
                <span class="badge"><?php echo $usersCount; ?></span>
            </div>

            <div class="col-xs-3 text-center">
                <div>
                    <a href="<?php echo Yii::app()->createUrl('user/view', ['id' => $user->id]); ?>?from=top_yurist_widget"
                       rel="nofollow">
                        <img src="<?php echo $user->getAvatarUrl(); ?>"
                             alt="<?php echo CHtml::encode($user->getNameOrCompany()); ?>"
                             class="img-responsive center-block gray-panel"/>
                    </a>
                </div>

            </div>
            <div class="col-xs-8">
                <div>
                    <a href="<?php echo Yii::app()->createUrl('user/view', ['id' => $user->id]); ?>?from=top_yurist_widget"
                       rel="nofollow">
                        <?php echo CHtml::encode($user->getNameOrCompany()); ?>
                    </a>
                    <div>
                    <span class="text-muted"><em>
                            <small>
                            <?php echo YuristSettings::getStatusNameByCode($user->settings->status); ?>
                            </small>
                        </em>
                    </span>

                        <?php if ($user->town->name): ?>
                            <em class="text-muted">
                                <small>
                                    (<?php echo $user->town->name; ?>)
                                </small>
                            </em>
                        <?php endif; ?>

                    </div>
                </div>


                <?php if (isset($userData['answersCount'])): ?>
                    <div>
                        <small>
                            <?php echo $userData['answersCount'] . ' ' . NumbersHelper::numForms($userData['answersCount'], 'консультация', 'консультации', 'консультаций'); ?>
                        </small>
                    </div>
                <?php endif; ?>

                <?php if (floor((time() - strtotime($user->lastActivity)) / 60) < 60): ?>
                    <div class="small"><span class="glyphicon glyphicon-flash"></span> <span class="text-success">Сейчас на сайте</span>
                    </div>
                <?php endif; ?>

            </div>


        </div> <!-- .row-yurist -->

    <?php endforeach; ?>
</div>
