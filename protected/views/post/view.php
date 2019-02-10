<?php
/* @var $this PostController */
/* @var $model Post */
$purifier = new Purifier();

$this->setPageTitle(CHtml::encode($model->title) . " " . "Консультации " . Yii::app()->name);
Yii::app()->clientScript->registerMetaTag($model->description, "Description");

Yii::app()->clientScript->registerLinkTag("canonical", NULL, Yii::app()->createUrl('post/view', array('id' => $model->id, 'alias' => $model->alias)));

$this->breadcrumbs = array(
    'Блог' => array('/blog'),
    CHtml::encode($model->title),
);
?>

<?php
$this->widget('zii.widgets.CBreadcrumbs', array(
    'homeLink' => CHtml::link('Консультация юриста', "/"),
    'separator' => ' / ',
    'links' => $this->breadcrumbs,
));
?>

<h1><?php echo CHtml::encode($model->title); ?></h1>

<div class="category-post-header">

    <?php if ($model->authorId == Yii::app()->user->id || Yii::app()->user->checkAccess(User::ROLE_EDITOR)): ?>
        <div>
            <i class="glyphicon glyphicon-edit"></i> <?php echo CHtml::link('Редактировать пост', Yii::app()->createUrl('post/update', array('id' => $model->id))); ?>
            &nbsp;&nbsp; 
            <i class="glyphicon glyphicon-remove"></i> <?php echo CHtml::link('Удалить пост', Yii::app()->createUrl('post/delete', array('id' => $model->id)), array('style' => 'color:#ff0000;')); ?>
        </div>
    <?php endif; ?>
</div>

<div class="post-preview">
    <?php
    // очищаем текст поста от ненужных тегов перед выводом в браузер

    echo $purifier->purify($model->preview);
    ?>
</div>

<?php if ($model->photo): ?>
    <div class="vert-margin30">
        <img src="<?php echo $model->getPhotoUrl(); ?>" alt="<?php echo CHtml::encode($model->title); ?>" class="img-responsive" />
    </div>
<?php endif; ?>

<div class="post-text">
    <?php
    // очищаем текст поста от ненужных тегов перед выводом в браузер

    echo $purifier->purify($model->text);
    ?>
</div>


<div class="post-stats">
    <div class='row'>
        <div class='col-md-3 col-sm-3'>
            <span class="muted"><?php echo CustomFuncs::invertDate($model->datePublication); ?></span>
        </div>
        <div class='col-md-5 col-sm-5 right-align'>
            <img src='/pics/2015/icon_eye.png' alt='просмотров' />&nbsp;<span class='muted'><?php echo $model->viewsCount->views; ?> <?php echo CustomFuncs::numForms($model->viewsCount->views, 'просмотр', "просмотра", "просмотров"); ?></span>
        </div>
        <div class='col-md-4 col-sm-4 right-align'>
            <script type="text/javascript" src="//yastatic.net/es5-shims/0.0.2/es5-shims.min.js" charset="utf-8"></script>
            <script type="text/javascript" src="//yastatic.net/share2/share.js" charset="utf-8"></script>
            <div class="ya-share2" data-services="vkontakte,facebook,odnoklassniki,moimir,twitter,lj"></div>
        </div>


    </div>
</div>

<hr />
<?php if (!Yii::app()->user->isGuest && !is_null($postCommentModel)): ?>
    <div class="vert-margin30">
    <strong>Ваш комментарий:</strong>
    <?php
    $this->renderPartial('application.views.comment._form', array(
        'type' => Comment::TYPE_POST,
        'objectId' => $model->id,
        'model' => $postCommentModel,
        'hideRating' => true,
        'parentId' => 0,
        'buttonText'    =>  'Комментировать'
    ));
    ?>
    </div>
<?php endif; ?>
    
<div class="post-comments-list vert-margin40">
    <h2>Комментарии</h2>
    <?php if ($commentsDataProvider): ?>
        <?php
        $this->widget('zii.widgets.CListView', array(
            'dataProvider' => $commentsDataProvider,
            'itemView' => '_viewComment',
            'emptyText' => 'Не найдено ни одного комментария. Ваш может стать первым!',
            'summaryText' => '',
            'viewData' => [
                'commentModel' => $postCommentModel,
            ],
            'pager' => array('class' => 'GTLinkPager') //we use own pager with russian words
        ));
        ?>
    <?php endif; ?>
</div>

<?php
$this->widget('application.widgets.RecentPosts.RecentPosts', [
    'number' => 3,
    'order' => 'views',
    'intervalDays' => 300,
    'template' => 'horizontal',
]);
?>

