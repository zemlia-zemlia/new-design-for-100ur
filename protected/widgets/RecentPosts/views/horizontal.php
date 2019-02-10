<?php

if (empty($recentPosts) || sizeof($recentPosts) == 0) {
    echo "Не найдено ни одного поста";
}
$purifier = new CHtmlPurifier();
?>

<div class="row">

    <?php foreach ($recentPosts as $recentPost): ?>
        <div class="col-md-4 horizontal-post-preview">
            <div class="row">
                <div class="col-md-4 center-align">

                    <?php if ($recentPost['photo']): ?>
                    <?php
                        $postObject = new Post();
                        $postObject->attributes = $recentPost;
                    ?>
                        <?php echo CHtml::link("<img src='" . $postObject->getPhotoUrl('thumb'). "' alt='" . CHtml::encode($recentPost['title']) . "'/>", Yii::app()->createUrl('post/view',array('id'=>$recentPost['id'], 'alias' => $recentPost['alias'])));?>
                    <?php endif; ?>
                </div>
                <div class="col-md-8">

                    <p class="center-mobile">
                        <?php echo CHtml::link(CHtml::encode($recentPost['title']), Yii::app()->createUrl('post/view', array('id' => $recentPost['id'], 'alias' => $recentPost['alias']))); ?>
                        <br/>
                    </p>
                    <p>
                        <small>
                            <?php echo CustomFuncs::cutString($purifier->purify($recentPost['preview']), 100); ?>
                        </small>
                    </p>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
