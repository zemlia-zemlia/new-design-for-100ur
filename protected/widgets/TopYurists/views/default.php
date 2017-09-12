<?php 
    $usersCount = 0;
?>

<div class="container-fluid">
<?php foreach ($users as $user):?>
    <?php if($usersCount%3 == 0) :?>
        <div class="row row-yurist">
    <?php endif;?>

    <div class="col-sm-4 vert-margin30">
        
        <div class="row">
            <div class="col-xs-4 text-center">
                    <a href="<?php echo Yii::app()->createUrl('user/view', array('id'=>$user['id']));?>" rel="nofollow">
                        <img class="img-responsive" src="<?php echo User::USER_PHOTO_PATH . User::USER_PHOTO_THUMB_FOLDER . '/'. $user['avatar'];?>" alt="<?php echo CHtml::encode($user['name'] . ' ' . $user['lastName']);?>" class="img-responsive center-block gray-panel" />
                    </a>
                    
            </div>
            <div class="col-xs-8">
                <div>
                <a href="<?php echo Yii::app()->createUrl('user/view', array('id'=>$user['id']));?>" rel="nofollow">
                    <?php echo CHtml::encode($user['name'] . ' ' . $user['lastName']);?>
                </a>
                </div>
                
                <div>
                    <small class="text-muted">
                    <?php echo YuristSettings::getStatusNameByCode($user['yuristStatus']);?>
                    </small>
                </div>
                
                <?php if(floor((time() - strtotime($user['lastActivity']))/60)<60):?>
                    <small><div><span class="glyphicon glyphicon-flash"></span> <span class="text-success">Сейчас на сайте</span></div></small>
                <?php endif;?>
                
                <p class="text-muted">
                    <small>
                    <?php if($user['townName']):?>
                        <?php echo $user['townName'];?><br />
                    <?php endif;?>
                    </small>
                </p>
                
                
                <?php if(sizeof($user['categories'])):?>
                
               <!--  <p><small>
                    <?php
                        $directions = array();
                        $directions = $user['categories'];

                        

                        foreach($user['categories'] as $cat) {
                            echo "<span class='yurist-directions-item'>" . $cat . '</span> ';
                        }
                    ?>
                    </small></p> -->
                <?php endif;?>
            </div>
        </div> 
    </div>
    <?php if($usersCount%3 == 2) :?>
        </div>
    <?php endif;?>

    <?php $usersCount++;?>
            
            
<?php endforeach; ?>
    <?php if($usersCount%3 == 2) :?>
        </div> <!-- .row-yurist -->
    <?php endif;?>
</div>   