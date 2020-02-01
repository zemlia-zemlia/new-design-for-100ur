<h3 class="header-bordered">Свежие статьи</h3>
<?php

if (empty($recentPosts) || sizeof($recentPosts)==0) {
    echo "Не найдено ни одного поста";
}
?>

<?php foreach ($recentPosts as $recentPost): ?>

<div class="row post-panel-item">
    <div class="col-md-4 post-panel-date">
                
        <?php if ($recentPost->photo):?>
            <?php echo CHtml::link("<img src='" . $recentPost->getPhotoUrl('thumb'). "' alt='" . CHtml::encode($recentPost->title) . "'/>", Yii::app()->createUrl('post/view', array('id'=>$recentPost->id)));?>
        <?php endif;?>
        <div class="post-panel-date-wrapper">
            <?php echo CustomFuncs::niceDate($recentPost->datePublication, false);?>
        </div>
    </div>
    <div class="col-md-8 post-panel-description">
        
        <p><?php echo CHtml::link(CHtml::encode($recentPost->title), Yii::app()->createUrl('post/view', array('id'=>$recentPost->id))); ?><br />
            <small>
            <?php echo nl2br(mb_substr(CHtml::encode($recentPost->preview), 0, 100, 'utf-8'));?>...
            </small>
        </p>
        
    </div>
</div>

<?php endforeach;?>
