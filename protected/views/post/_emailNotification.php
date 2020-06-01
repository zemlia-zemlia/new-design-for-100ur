<?php

/*  Шаблон письма с новыми публикациями в категориях, на которые подписан пользователь
 * $follower - массив с информацией о пользователе и категориях, на которые подписан пользователь,  новых постах в них
 */
?>
<h1>
<?php if ($follower['name']):?>
<?php echo $follower['name'] . ', '; ?>
<?php endif; ?>
Обновления в интересных Вам категориях</h1>
<?php foreach ($follower['posts'] as $cat): ?>
    <?php foreach ($cat as $postId => $post):?>
        <p style='margin:20px 0;'><?php echo CHtml::link(CHtml::encode($post['title']), 'http://www.poehali-vmeste.net' . Yii::app()->createUrl('post/view', ['id' => $postId])); ?><br />
		<?php echo CHtml::encode($post['preview']); ?> 
</p>
    <?php endforeach; ?>
<?php endforeach; ?>
<p>
Если Вы захотите отписаться от уведомлений в какой-либо категории, Вы всегда можете сделать это на странице категории.
</p>



