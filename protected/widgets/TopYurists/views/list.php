<?php

use App\models\User;
use App\models\YuristSettings;
/** @var User[] $usersData */
$usersCount = 0;
?>

<div class="container-fluid">
    <?php foreach ($usersData as $user): ?>

        <div class="row row-yurist yurist-list-item">

            <div class="col-xs-3 text-center">
                <a href="<?php echo Yii::app()->createUrl('user/view', ['id' => $user->id]); ?>" rel="nofollow">
                    <img src="<?php echo $user->getAvatarUrl(); ?>" alt="<?php echo CHtml::encode($user->getNameOrCompany()); ?>" class="img-responsive center-block gray-panel" />
                </a>

            </div>
            <div class="col-xs-9">
                <div>
                    <?php if ($user->town->name): ?>
                        <div style="float:right;">
                            <em class="text-muted">
                                <small>
                                    <?php echo $user->town->name; ?>
                                </small>
                            </em>
                        </div>
                    <?php endif; ?>

                    <a href="<?php echo Yii::app()->createUrl('user/view', ['id' => $user->id]); ?>" rel="nofollow">
                        <?php echo CHtml::encode($user->getNameOrCompany()); ?>
                    </a>
                    <span class="text-muted"><em>
                            <?php echo YuristSettings::getStatusNameByCode($user->settings->status); ?>
                        </em>
                    </span>
                </div>

                <?php if (floor((time() - strtotime($user->lastActivity)) / 60) < 60): ?>
                    <div class="small"><span class="glyphicon glyphicon-flash"></span> <span class="text-success">Сейчас на сайте</span></div>
                <?php endif; ?>


                <strong>Карма:</strong> <?php echo (int) $user->karma; ?>

                <?php if ($user->settings->priceConsult): ?>
                    <br />
                    <strong>Консультация:</strong> от <?php echo $user->settings->priceConsult; ?> <span class="glyphicon glyphicon-ruble"></span>
                <?php endif; ?>
                <?php if ($user->settings->priceDoc): ?>
                    <br />
                    <strong>Документ:</strong> от <?php echo $user->settings->priceDoc; ?>  <span class="glyphicon glyphicon-ruble"></span>
                <?php endif; ?>

            </div>


        </div> <!-- .row-yurist -->    

    <?php endforeach; ?>


</div>   