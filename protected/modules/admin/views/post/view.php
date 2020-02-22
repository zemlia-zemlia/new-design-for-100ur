<?php
/* @var $this PostController */
/* @var $model Post */
$purifier = new Purifier();

$this->setPageTitle(CHtml::encode($model->title) . ' | Блог' . ' | ' . Yii::app()->name);
Yii::app()->clientScript->registerMetaTag($purifier->purify($model->preview), 'Description');

$this->breadcrumbs = [
    'Блог' => ['/admin/blog'],
    CHtml::encode($model->title),
];

?>

<?php
$this->widget('zii.widgets.CBreadcrumbs', [
    'homeLink' => CHtml::link('100 Юристов', '/admin/'),
    'separator' => ' / ',
    'links' => $this->breadcrumbs,
]);
?>

<h1><?php echo CHtml::encode($model->title); ?></h1>

<?php echo $model->author->name . ' ' . $model->author->lastName; ?> &nbsp;
<span class="muted"><?php echo CustomFuncs::invertDate($model->datePublication); ?></span>


<?php if ($model->photo): ?>
    <div>
        <img class="img-responsive" src="<?php echo $model->getPhotoUrl(); ?>" alt=""/>
    </div>
<?php endif; ?>

<div class="category-post-header">


    <?php if ($model->authorId == Yii::app()->user->id || Yii::app()->user->checkAccess(User::ROLE_EDITOR)): ?>
        <div>
            <i class="glyphicon glyphicon-edit"></i> <?php echo CHtml::link('Редактировать пост', Yii::app()->createUrl('/admin/post/update', ['id' => $model->id])); ?>
            &nbsp;&nbsp;
            <?php if (Yii::app()->user->checkAccess(User::ROLE_ROOT)): ?>
                <i class="glyphicon glyphicon-remove"></i> <?php echo CHtml::link('Удалить пост', Yii::app()->createUrl('/admin/post/delete', ['id' => $model->id]), ['style' => 'color:#ff0000;']); ?>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<div class="category-post-body">
    <div class="post-preview">
        <?php
        // очищаем текст поста от ненужных тегов перед выводом в браузер

        echo $purifier->purify($model->preview);
        ?>
    </div>
    <div class="post-text">
        <?php
        // очищаем текст поста от ненужных тегов перед выводом в браузер

        echo $purifier->purify($model->text);
        ?>
    </div>

    <?php
    // если пользователь залогинен и уже голосовал за этот пост, выводим иконку сердечка полупрозрачной
    $likeClass = (true === $postLiked) ? 'transparent' : '';

    ?>
    <div class="post-stats">
        <i class="glyphicon glyphicon-comment"></i>&nbsp;<?php echo $model->commentsCount; ?>
        &nbsp;

        <i class="glyphicon glyphicon-eye-open"></i>&nbsp;<?php echo $model->viewsCount->views; ?>
    </div>


    <?php if (is_array($relatedPosts) && sizeof($relatedPosts) > 0): ?>
        <div class="post-related-list">
            <h2>Похожие посты</h2>
            <?php foreach ($relatedPosts as $relatedPost): ?>
                <div class="related-posts-item"><?php echo CHtml::link(CHtml::encode($relatedPost->title), Yii::app()->createUrl('/admin/post/view', ['id' => $relatedPost->id])); ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>


    <div class="post-comments-list">
        <h2>Комментарии
            <?php if (!Yii::app()->user->isGuest): ?>
                <a href="#post-comment-form">написать новый</a>
            <?php endif; ?>
        </h2>
        <?php if ($commentsDataProvider): ?>
            <?php $this->widget('zii.widgets.CListView', [
                'dataProvider' => $commentsDataProvider,
                'itemView' => 'application.modules.admin.views.post._viewComment',
                'emptyText' => 'Не найдено ни одного комментария. Ваш может стать первым!',
                'summaryText' => '',
                'pager' => ['class' => 'GTLinkPager'], //we use own pager with russian words
            ]); ?>
        <?php endif; ?>
    </div>


    <?php if (!Yii::app()->user->isGuest): ?>
        <?php echo $this->renderPartial('_commentForm', [
            'model' => $postCommentModel,
        ]);
        ?>
    <?php endif; ?>

</div>
<div class="clearfix"></div>

