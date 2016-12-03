<?php
/* @var $this PostController */
/* @var $model Post */
$purifier = new Purifier();

$this->setPageTitle(CHtml::encode($model->title) . " | Блог" . " | ". Yii::app()->name);
Yii::app()->clientScript->registerMetaTag($purifier->purify($model->preview), "Description");

$this->breadcrumbs=array(
	'Блог'=>array('/admin/blog'),
	CHtml::encode($model->title),
);

?>

<?php
    $this->widget('zii.widgets.CBreadcrumbs', array(
        'homeLink'=>CHtml::link('CRM',"/"),
        'separator'=>' / ',
        'links'=>$this->breadcrumbs,
     ));
?>

<h1><?php echo CHtml::encode($model->title); ?></h1>

            
<div class="right-align">
<?php echo CHtml::link('Написать пост', Yii::app()->createUrl((Yii::app()->user->isGuest)?'site/login':'/admin/post/create'), array('class'=>'btn btn-primary'));?>
</div>

    <?php echo $model->author->name . ' ' . $model->author->lastName;?> &nbsp;
    <span class="muted"><?php echo CustomFuncs::invertDate($model->datePublication);?></span>

          
    <?php if($model->photo):?>
    <div>
    <img src="<?php echo $model->getPhotoUrl();?>" alt="" />
    </div>
    <?php endif;?>
    
        <div class="category-post-header">

            
            <?php if($model->authorId == Yii::app()->user->id || Yii::app()->user->checkAccess(User::ROLE_EDITOR)):?>
            <div>
                <i class="glyphicon glyphicon-edit"></i> <?php echo CHtml::link('Редактировать пост', Yii::app()->createUrl('/admin/post/update', array('id'=>$model->id)));?>
                &nbsp;&nbsp; 
                <i class="glyphicon glyphicon-remove"></i> <?php echo CHtml::link('Удалить пост', Yii::app()->createUrl('/admin/post/delete', array('id'=>$model->id)), array('style'=>'color:#ff0000;'));?>
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
                if($postLiked === true) {
                    $likeClass = 'transparent';
                } else {
                    $likeClass = '';
                }
               
            ?>
            <div class="post-stats">
                <i class="glyphicon glyphicon-comment"></i>&nbsp;<?php echo $model->commentsCount;?>
                &nbsp;
  
                <i class="glyphicon glyphicon-eye-open"></i>&nbsp;<?php echo $model->viewsCount->views;?>
            </div>
                        
            
            <?php if(sizeof($relatedPosts)>0):?>
            <div class="post-related-list">
                <h2>Похожие посты</h2>
                <?php foreach($relatedPosts as $relatedPost):?>
                    <div class="related-posts-item"><?php echo CHtml::link(CHtml::encode($relatedPost->title),Yii::app()->createUrl('/admin/post/view',array('id'=>$relatedPost->id))); ?></div>
                <?php endforeach;?>
            </div>
            <?php endif;?>
            
            
            <div class="post-comments-list">
                <h2>Комментарии
                <?php if(!Yii::app()->user->isGuest):?>
                    <a href="#post-comment-form">написать новый</a>
                <?php endif;?>
                </h2>
                <?php if($commentsDataProvider):?>
                <?php $this->widget('zii.widgets.CListView', array(
                        'dataProvider'  =>  $commentsDataProvider,
                        'itemView'      =>  'application.modules.admin.views.post._viewComment',
                        'emptyText'     =>  'Не найдено ни одного комментария. Ваш может стать первым!',
                        'summaryText'   =>  '',
                        'pager'=>array('class'=>'GTLinkPager') //we use own pager with russian words
                )); ?>
                <?php endif;?>
            </div>
            
            
            <?php if(!Yii::app()->user->isGuest):?>
            <?php echo $this->renderPartial('_commentForm', array(
                    'model'     =>  $postCommentModel,
                )); 
            ?>
            <?php endif;?>
            
        </div>
        <div class="clearfix"></div>

