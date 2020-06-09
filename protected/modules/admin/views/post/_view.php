<?php
    /*
     * Отображение поста в списке постов
     * $data - текущий пост
     */
?>

<div class="category-post">
    
    <?php use App\helpers\DateHelper;

if ($data->photo):?>
    <div>
    <img src="<?php echo $data->getPhotoUrl('thumb'); ?>" alt="" style="float:left; margin-right:20px;" />
    </div>
    <?php endif; ?>
    
    <div class="category-post-header">
        
        <h3>
            <?php echo CHtml::link(CHtml::encode($data->title), Yii::app()->createUrl('/admin/post/view', ['id' => $data->id])); ?>
        </h3>
    </div>
    
    <div class="category-post-body">

        <div class="category-post-preview">
            <?php
                // очищаем текст поста от ненужных тегов перед выводом в браузер
                $purifier = new Purifier();
                echo $purifier->purify($data->preview);
            ?>
        </div>
        
        <div class="post-stats">
            <i class="glyphicon glyphicon-comment"></i>&nbsp;<?php echo $data->commentsCount; ?>
            &nbsp; 
            <i class="glyphicon glyphicon-heart"></i>&nbsp;<?php echo $data->rating; ?> &nbsp; 
            <i class="glyphicon glyphicon-eye-open"></i>&nbsp;<?php echo $data->viewsCount->views; ?>
			&nbsp;&nbsp;
			<?php echo $data->author->name; ?>
            <span class="muted"><?php echo DateHelper::invertDate($data->datePublication); ?></span>
            
            <?php
                $now = time();
                $pubTime = strtotime($data->datePublication);
                if ($pubTime > $now) {
                    echo "<span class='label label-warning'>Отложенная публикация</span>";
                }

            ?>
        </div>
        
    </div>
    <div class="clearfix"></div>
</div>