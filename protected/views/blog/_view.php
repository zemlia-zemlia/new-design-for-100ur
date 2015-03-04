<?php
/* @var $this CategoryController */
?>

<div class="category-post">
    <div class="category-post-header">
        <span class="muted"><?php echo CustomFuncs::invertDate($data->datePublication);?></span>
        <h3>
            <?php echo CHtml::link(CHtml::encode($data->title), Yii::app()->createUrl('post/view',array('id'=>$data->id)));?>
        </h3>
    </div>
    
    <div class="category-post-body">

        <div class="category-post-preview">
            <?php
                // очищаем текст поста от ненужных тегов перед выводом в браузер
                $purifier = new Purifier();
                echo $purifier->purify($data->preview) . ' ' . CHtml::link('читать весь пост', Yii::app()->createUrl('post/view',array('id'=>$data->id))); 
            ?>
        </div>
        
        <div class="post-stats">
            <i class="glyphicon glyphicon-eye-open"></i>&nbsp;<?php echo $data->viewsCount->views;?>
        </div>
        
    </div>
    <div class="clearfix"></div>
</div>