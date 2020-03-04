<?php
$usersCount = 0;
?>

<div class="container-fluid">
    <?php foreach ($users as $user): ?>

        <div class="row row-yurist yurist-list-item">

            <div class="col-xs-3 text-center">
                <a href="<?php echo Yii::app()->createUrl('user/view', ['id' => $user['id']]); ?>" rel="nofollow">
                    <img src="<?php echo User::USER_PHOTO_PATH . User::USER_PHOTO_THUMB_FOLDER . '/' . $user['avatar']; ?>" alt="<?php echo CHtml::encode($user['name'] . ' ' . $user['lastName']); ?>" class="img-responsive center-block gray-panel" />
                </a>

            </div>
            <div class="col-xs-9">
                <div>
                    <?php if ($user['townName']): ?>
                        <div style="float:right;">
                            <em class="text-muted">
                                <small>
                                    <?php echo $user['townName']; ?>
                                </small>
                            </em>
                        </div>
                    <?php endif; ?>

                    <a href="<?php echo Yii::app()->createUrl('user/view', ['id' => $user['id']]); ?>" rel="nofollow">
                        <?php echo CHtml::encode($user['name'] . ' ' . $user['lastName']); ?>
                    </a>
                    <span class="text-muted"><em>
                            <?php echo YuristSettings::getStatusNameByCode($user['yuristStatus']); ?>
                        </em>
                    </span>
                </div>

                <?php if (floor((time() - strtotime($user['lastActivity'])) / 60) < 60): ?>
                    <div class="small"><span class="glyphicon glyphicon-flash"></span> <span class="text-success">Сейчас на сайте</span></div>
                <?php endif; ?>


                <strong>Карма:</strong> <?php echo (int) $user['karma']; ?>

                <?php if ($user['priceConsult']): ?>
                    <br />
                    <strong>Консультация:</strong> от <?php echo $user['priceConsult']; ?> <span class="glyphicon glyphicon-ruble"></span>
                <?php endif; ?>
                <?php if ($user['priceDoc']): ?>
                    <br />
                    <strong>Документ:</strong> от <?php echo $user['priceDoc']; ?>  <span class="glyphicon glyphicon-ruble"></span>
                <?php endif; ?>

            </div>


        </div> <!-- .row-yurist -->    

    <?php endforeach; ?>


</div>   