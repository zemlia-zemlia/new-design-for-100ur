<?php

use App\helpers\NumbersHelper;
use App\models\User;
use App\models\YuristSettings;

?>




<div class="best-workers">
    <h3 class="best-workers__title">Наши лучшие юристы</h3>
    <?php foreach ($usersData as $userData): ?>
    <?php
    /** @var User $user */
    $user = $userData['user'];
    ?>
    <div class="best-workers__item">
        <div class="best-workers__avatar img">
            <img src="<?= $user->getAvatarUrl() ?>" alt="<?= CHtml::encode($user->getNameOrCompany()) ?>">
             <?php if (floor((time() - strtotime($user->lastActivity)) / 60) < 60): ?>
            <div class="best-workers__avatar-online"></div>
             <?php endif; ?>
        </div>
        <div class="best-workers__data">
            <a href="<?= Yii::app()->createUrl('user/view', ['id' => $user->id]) ?>?from=top_yurist_widget" class="best-workers__name">
                <?= CHtml::encode($user->getNameOrCompany()) ?></a>
            <div class="best-workers__data-wrapper">
                <div class="best-workers__specialty"><?= YuristSettings::getStatusNameByCode($user->settings->status) ?></div>
                <div class="best-workers__location">
                    <div class="best-workers__location-ico img">
                        <img src="/img/unregistered/best-workers-location-ico.png" alt="">
                    </div>
                    <div class="best-workers__location-value">

                        <?= $user->town ? $user->town->name : ''?>

                    </div>
                </div>
            </div>
            <div class="best-workers__activity">
                <div class="best-workers__activity-value">
                    <?php if (isset($userData['answersCount'])): ?>
                        <?= $userData['answersCount'] ?>
                    <?php endif; ?>
                </div>
                <div class="best-workers__activity-title">
                    <?php if (isset($userData['answersCount'])): ?>
                        <?=  NumbersHelper::numForms($userData['answersCount'], 'консультация', 'консультации', 'консультаций') ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

<?php endforeach; ?>

    <a href="<?= Yii::app()->createUrl('/yurist/russia/') ?>" class="best-workers__btn main-btn">Все наши юристы</a>
</div>

