<?php
/* @var $this PostController */
/* @var $model Post */
$purifier = new Purifier();

$this->setPageTitle(CHtml::encode($model->title) . " | Блог" . " | ". Yii::app()->name);
Yii::app()->clientScript->registerMetaTag($purifier->purify($model->preview), "Description");

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

<div class="row">
    <div class="col-md-8">
          
        <div class="category-post-header">
            <h1><?php echo CHtml::encode($model->title); ?></h1>
            <div class="category-post-categories">
                <span class="glyphicon glyphicon-tags"></span> &nbsp;
                <?php foreach($model->categories as $category) {
                    echo CHtml::link(CHtml::encode($category->title), Yii::app()->createUrl('blog/view',array('id'=>$category->id))) . "&nbsp;&nbsp; ";
                }
                ?>
            </div>
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
    </div> <!-- .col-md-8 -->
    
    <div class="col-md-4">
        <div class="vert-margin40 rounded side-block">
            <h3>Популярные посты</h3>
            <?php
                // выводим виджет с популярными постами
                $this->widget('application.widgets.posts.Posts', array(
                ));
            ?>
        </div>
    </div>
</div>
