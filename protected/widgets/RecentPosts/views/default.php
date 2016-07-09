
<div class="panel gray-panel">
    <div class="panel-body">
        <h3>Свежие статьи</h3>
        <?php

        if(empty($recentPosts) || sizeof($recentPosts)==0) {
            echo "Не найдено ни одного поста";
        }
        ?>

        <?php foreach($recentPosts as $recentPost): ?>
        <p><?php echo CHtml::link(CHtml::encode($recentPost->title), Yii::app()->createUrl('post/view',array('id'=>$recentPost->id))); ?><br />
        <?php echo CHtml::encode($recentPost->preview);?>
        </p>
        <?php endforeach;?>
    </div>
</div>