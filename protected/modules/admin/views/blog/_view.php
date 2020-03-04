<?php
/* @var $this CategoryController */

?>

<div class="category-post">
    <div class="category-post-header">
        <?php echo $data->author->name; ?>
        <span class="muted">
        <?php if (strtotime($data->datePublication) > time()):?>
            <span class="label label-warning">ожидает публикации</span>
        <?php endif; ?>    
            <?php echo DateHelper::invertDate($data->datePublication); ?></span>
        <h3>
            <?php echo CHtml::link(CHtml::encode($data->title), Yii::app()->createUrl('/admin/post/view', ['id' => $data->id])); ?>
        </h3>
    </div>
    
    <div class="category-post-body">

        <div class="category-post-preview">
            <?php
                // очищаем текст поста от ненужных тегов перед выводом в браузер
                $purifier = new Purifier();
                echo $purifier->purify($data->preview) . ' ' . CHtml::link('читать весь пост', Yii::app()->createUrl('/admin/post/view', ['id' => $data->id]));
            ?>
        </div>
        
        <div class="post-stats">
            <i class="glyphicon glyphicon-comment"></i>&nbsp;<?php echo $data->commentsCount; ?>
            &nbsp; 
            <i class="glyphicon glyphicon-eye-open"></i>&nbsp;<?php echo $data->viewsCount->views; ?>
        </div>
        
    </div>
    <div class="clearfix"></div>
</div>