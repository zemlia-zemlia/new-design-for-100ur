<?php
/* @var $this PostController */
/* @var $model Post */
$purifier = new Purifier();

$this->setPageTitle(CHtml::encode($model->title) . " | Блог" . " | ". Yii::app()->name);
Yii::app()->clientScript->registerMetaTag($purifier->purify($model->description), "Description");

$this->breadcrumbs=array(
	'Блог'=>array('/blog'),
	CHtml::encode($model->title),
);

?>

<?php
    $this->widget('zii.widgets.CBreadcrumbs', array(
        'homeLink'=>CHtml::link('Консультация юриста',"/"),
        'separator'=>' / ',
        'links'=>$this->breadcrumbs,
     ));
?>

<h1><?php echo CHtml::encode($model->title); ?></h1>


<span class="muted"><?php echo CustomFuncs::invertDate($model->datePublication);?></span>

          
        <div class="category-post-header">

            
            <?php if($model->authorId == Yii::app()->user->id || Yii::app()->user->checkAccess('moderator')):?>
            <div>
                <i class="glyphicon glyphicon-edit"></i> <?php echo CHtml::link('Редактировать пост', Yii::app()->createUrl('post/update', array('id'=>$model->id)));?>
                &nbsp;&nbsp; 
                <i class="glyphicon glyphicon-remove"></i> <?php echo CHtml::link('Удалить пост', Yii::app()->createUrl('post/delete', array('id'=>$model->id)), array('style'=>'color:#ff0000;'));?>
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
                <i class="glyphicon glyphicon-eye-open"></i>&nbsp;<?php echo $model->viewsCount->views;?>
            </div>
                        
            
            <?php if(sizeof($relatedPosts)>0):?>
            <div class="post-related-list">
                <h2>Похожие посты</h2>
                <?php foreach($relatedPosts as $relatedPost):?>
                    <div class="related-posts-item"><?php echo CHtml::link(CHtml::encode($relatedPost->title),Yii::app()->createUrl('post/view',array('id'=>$relatedPost->id))); ?></div>
                <?php endforeach;?>
            </div>
            <?php endif;?>
            
            
        </div>
        <div class="clearfix"></div>
