<?php foreach ($users as $user):?>

    <div class="col-sm-6 vert-margin30">
        
        <div class="row">
            <div class="col-sm-4">
                <a href="<?php echo Yii::app()->createUrl('user/view', array('id'=>$user->id));?>">
                    <img src="<?php echo $user->getAvatarUrl();?>" class="img-responsive center-block gray-panel" />
                </a>
            </div>
            <div class="col-sm-8">
                <p>
                <a href="<?php echo Yii::app()->createUrl('user/view', array('id'=>$user->id));?>">
                    <?php echo ($user->settings->alias)?CHtml::encode($user->settings->alias):CHtml::encode($user->name . ' ' . $user->lastName);?>
                </a>
                </p>
                
                <?php if($user->categories):?>
                <p><small>
                    <?php
                        $directions = array();
                        $directions = $user->categories;

                        if(sizeof($directions) > 3) {
                            shuffle($directions);
                        }

                        for($i = 0; $i<3; $i++) {
                            echo "<span class='label label-default'>" . $directions[$i]->name . '</span> ';
                        }
                    ?>
                    </small></p>
                <?php endif;?>
                <a href="/question/create/?utm_source=100yuristov&utm_campaign=yuristi&utm_medium=button&utm_content=<?php echo CHtml::encode($user->name . '_' . $user->lastName);?>" class="btn btn-warning btn-xs" rel="nofollow">Обратиться к юристу</a>
            </div>
        </div>  
    </div>

<?php endforeach; ?>
