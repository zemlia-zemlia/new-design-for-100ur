<?php

if(empty($popularPosts) || sizeof($popularPosts)==0) {
    echo "Не найдено ни одного поста";
}
?>

<?php foreach($popularPosts as $popularPost): ?>
    <p><?php echo CHtml::link(CHtml::encode($popularPost->title), Yii::app()->createUrl('post/view',array('id'=>$popularPost->id))); ?></p>
<?php endforeach;?>


