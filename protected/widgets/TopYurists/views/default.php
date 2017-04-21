<?php 
    $usersCount = 0;
?>

<?php foreach ($users as $user):?>
    <?php if($usersCount%2 == 0) :?>
        <div class="row">
    <?php endif;?>

    <div class="col-sm-6 vert-margin30">
        
        <div class="row">
            <div class="col-sm-4 text-center">
                <a href="<?php echo Yii::app()->createUrl('user/view', array('id'=>$user['id']));?>">
                    <img class="img-responsive img-bordered" src="<?php echo User::USER_PHOTO_PATH . User::USER_PHOTO_THUMB_FOLDER . '/'. $user['avatar'];?>" alt="<?php echo CHtml::encode($user['name'] . ' ' . $user['lastName']);?>" class="img-responsive center-block gray-panel" />
                </a>
            </div>
            <div class="col-sm-8 ">
                <p>
                <a href="<?php echo Yii::app()->createUrl('user/view', array('id'=>$user['id']));?>" rel="nofollow">
                    <?php echo ($user['alias'])?CHtml::encode($user['alias']):CHtml::encode($user['name'] . ' ' . $user['lastName']);?>
                </a>
                </p>
                <p class="text-muted">
                    <small>
                    <?php if($user['town']):?>
                        <?php echo $user['town'];?><br />
                    <?php endif;?>
                    Ответов: <?php echo $user['answersCount'];?>
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
                <a href="/question/create/?utm_source=100yuristov&utm_campaign=yuristi&utm_medium=button&utm_content=<?php echo CHtml::encode($user['name'] . '_' . $user['lastName']);?>" class="btn btn-warning btn-xs" rel="nofollow">Обратиться к юристу</a>
            </div>
        </div>  
    </div>
    <?php if($usersCount%2 == 1) :?>
        </div>
    <?php endif;?>

    <?php $usersCount++;?>
            
            
<?php endforeach; ?>
    <?php if($usersCount%2 == 1) :?>
        </div>
    <?php endif;?>
