<?php
$purifier = new CHtmlPurifier();
?>

<?php

if (empty($recentPosts) || sizeof($recentPosts) == 0) {
    echo "Не найдено ни одного поста";
}
?>
<div class="posts-widget-default">

    <?php foreach ($recentPosts as $recentPost): ?>
        <div class="post-widget-item">
            <h5 class="text-left">
                <strong>
                    <?php echo CHtml::link(CHtml::encode($recentPost['title']), Yii::app()->createUrl('post/view', array('id' => $recentPost['id'], 'alias' => $recentPost['alias']))); ?>
                </strong>
            </h5>
            <div class="text-right">
                <small>
                    <span class="glyphicon glyphicon-eye-open"></span> <?php echo $recentPost['viewsCount']; ?>
                    &nbsp;&nbsp;
                    <span class="glyphicon glyphicon glyphicon-comment"></span> <?php echo $recentPost['comments']; ?>

                </small>
            </div>
        </div>
    <?php endforeach; ?>
</div>