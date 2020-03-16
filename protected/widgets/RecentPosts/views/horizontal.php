<?php

use App\models\Post;

if (empty($recentPosts) || 0 == sizeof($recentPosts)) {
    echo 'Не найдено ни одного поста';
}
$purifier = new CHtmlPurifier();
?>



<?php foreach ($recentPosts as $index => $recentPost): ?>

    <?php if (0 == $index % 3): ?>
        <div class="row vert-margin20">
    <?php endif; ?>

    <div class="col-md-4 horizontal-post-preview">
        <div class="row">
            <div class="col-md-4 center-align">

                <?php if ($recentPost['photo']): ?>
                    <?php
                    $postObject = new Post();
                    $postObject->attributes = $recentPost;
                    ?>
                    <?php echo CHtml::link("<img src='" . $postObject->getPhotoUrl('thumb') . "' alt='" . CHtml::encode($recentPost['title']) . "'/>", Yii::app()->createUrl('post/view', ['id' => $recentPost['id'], 'alias' => $recentPost['alias']])); ?>
                <?php endif; ?>
            </div>
            <div class="col-md-8">

                <p class="center-mobile">
                    <?php echo CHtml::link(CHtml::encode($recentPost['title']), Yii::app()->createUrl('post/view', ['id' => $recentPost['id'], 'alias' => $recentPost['alias']])); ?>
                    <br/>
                </p>

            </div>
        </div>
    </div>
    <?php if (2 == $index % 3): ?>
        </div>
    <?php endif; ?>
<?php endforeach; ?>

