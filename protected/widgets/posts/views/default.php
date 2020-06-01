<?php

if (empty($popularPosts) || 0 == sizeof($popularPosts)) {
    echo 'Не найдено ни одного поста';
}
?>

<?php foreach ($popularPosts as $popularPost): ?>
    <p><?php echo CHtml::link(CHtml::encode($popularPost->title), Yii::app()->createUrl('post/view', ['id' => $popularPost->id])); ?></p>
<?php endforeach; ?>


