<?php

use App\models\User;

$usersCount = 0;
?>

<div class="container-fluid">
<?php foreach ($users as $user):?>
    <?php if (0 == $usersCount % 3) :?>
        <div class="row row-yurist">
    <?php endif; ?>

    <div class="col-sm-4 vert-margin30">
        
        <div class="row">
            <div class="col-xs-4 text-center">
                    <a href="<?php echo Yii::app()->createUrl('user/view', ['id' => $user['id']]); ?>" rel="nofollow">
                        <img src="<?php echo User::USER_PHOTO_PATH . User::USER_PHOTO_THUMB_FOLDER . '/' . $user['avatar']; ?>" alt="<?php echo CHtml::encode($user['name'] . ' ' . $user['lastName']); ?>" class="img-responsive center-block gray-panel" />
                    </a>
                    
            </div>
            <div class="col-xs-8">
                <div>
                <a href="<?php echo Yii::app()->createUrl('user/view', ['id' => $user['id']]); ?>" rel="nofollow">
                    <?php echo CHtml::encode($user['name'] . ' ' . $user['lastName']); ?>
                </a>
                </div>
                
                <div>
                </div>
                
                <?php if (floor((time() - strtotime($user['lastActivity'])) / 60) < 60):?>
                    <div class="small"><span class="glyphicon glyphicon-flash"></span> <span class="text-success">Сейчас на сайте</span></div>
                <?php endif; ?>
                
                <p class="text-muted">
                    <small>
                    <?php if ($user['townName']):?>
                        <?php echo $user['townName']; ?><br />
                    <?php endif; ?>
                    </small>
                </p>
                
            </div>
        </div> 
    </div>
    <?php if (2 == $usersCount % 3) :?>
        </div>
    <?php endif; ?>

    <?php ++$usersCount; ?>
            
            
<?php endforeach; ?>
    <?php if (2 == $usersCount % 3) :?>
        </div> <!-- .row-yurist -->
    <?php endif; ?>
</div>   