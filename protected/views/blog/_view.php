<?php
/* @var $this CategoryController */
?>

    <div class="row">
        <div class="col-sm-3 col-xs-12 center-align">
            <?php if($data->photo):?>
                <a href="<?php echo Yii::app()->createUrl('post/view',array('id'=>$data->id));?>">
                    <img src="<?php echo $data->getPhotoUrl();?>" alt="<?php echo CHtml::encode($data->title);?>" class="vert-margin20" />
                </a>
            <?php endif;?>
        </div>
        
        <div class="col-sm-9 col-xs-12">
            <div class="category-post-header">
            <h3>
                <?php echo CHtml::link(CHtml::encode($data->title), Yii::app()->createUrl('post/view',array('id'=>$data->id)));?>
            </h3>
            </div>

            <div class="category-post-preview vert-margin20">
                <?php
                    // очищаем текст поста от ненужных тегов перед выводом в браузер
                    $purifier = new Purifier();
                    echo $purifier->purify($data->preview); 
                ?>
            </div>
        
            <small>
                <div class="post-stats">
                        <div class='row'>
                            <div class='col-xs-6 left-align'>
                                <span class="muted"><?php echo CustomFuncs::invertDate($data->datePublication);?></span>
                            </div>
                            <div class='col-xs-6 right-align'>
                                <span class="glyphicon glyphicon-eye-open"></span>&nbsp;<span class='muted'><?php echo $data->viewsCount->views;?> </span>
                            </div>
                        </div>

                </div>
            </small>
        </div>
    </div>
<hr />
