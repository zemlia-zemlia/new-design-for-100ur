<h3 class="header-bordered">Свежие статьи</h3>
<?php

if(empty($recentPosts) || sizeof($recentPosts)==0) {
    echo "Не найдено ни одного поста";
}
?>

<div class="row">

<?php foreach($recentPosts as $recentPost): ?>
    <div class="col-md-4">
        <div class="row">
            <div class="col-md-4 center-align">

                <?php if($recentPost->photo):?>
                    <?php echo CHtml::link("<img src='" . $recentPost->getPhotoUrl('thumb'). "' alt='" . CHtml::encode($recentPost->title) . "'/>", Yii::app()->createUrl('post/view',array('id'=>$recentPost->id)));?>
                <?php endif;?>
                <div class="post-panel-date-wrapper">
                    <?php echo CustomFuncs::niceDate($recentPost->datePublication, false);?>
                </div>
            </div>
            <div class="col-md-8">

                <p class="center-mobile">
                    <?php echo CHtml::link(CHtml::encode($recentPost->title), Yii::app()->createUrl('post/view',array('id'=>$recentPost->id))); ?><br />
                </p>
                <p>
                    <small>
                    <?php echo nl2br(mb_substr(CHtml::encode($recentPost->preview),0,100,'utf-8'));?>...
                    </small>
                </p>
                <br />

            </div>
        </div>
    </div>
<?php endforeach;?>
</div>
