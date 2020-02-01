<?php
/* @var $this CategoryController */


switch ($index) {
    case 0:
        $postWrapperClass = "row post-hero";
        $photoBlockClass = "col-xs-12";
        $textBlockClass = "col-xs-12 text-over-hero";
        break;
    case 1: case 2:
        $postWrapperClass = "col-sm-6";
        $photoBlockClass = "";
        $textBlockClass = "";
        break;
    default:
        $postWrapperClass = "row";
        $photoBlockClass = "col-sm-3 col-xs-12";
        $textBlockClass = "col-sm-9 col-xs-12";
}
?>

<?php if ($index==1):?>
    <div class="row">
<?php endif;?>
    
    
    <div class="<?php echo $postWrapperClass; ?>">
        <div class="<?php echo $photoBlockClass; ?>  center-align">
            <?php if ($data->photo): ?>
                <a href="<?php echo Yii::app()->createUrl('post/view', array('id' => $data->id, 'alias' => $data->alias)); ?>">
                    <img src="<?php echo $data->getPhotoUrl(); ?>" alt="<?php echo CHtml::encode($data->title); ?>" class="vert-margin20" />
                </a>
            <?php endif; ?>
        </div>

        <div class="<?php echo $textBlockClass; ?>">
            <div class="category-post-header">
                <h3>
                    <?php echo CHtml::link(CHtml::encode($data->title), Yii::app()->createUrl('post/view', array('id' => $data->id, 'alias' => $data->alias))); ?>
                </h3>
            </div>

            <div class="category-post-preview vert-margin20">
                <?php
                // очищаем текст поста от ненужных тегов перед выводом в браузер
                $purifier = new Purifier();
                echo $purifier->purify($data->preview);
                ?>
            </div>

            <small>
                <div class="post-stats">
                    <div class='row'>
                        <div class='col-xs-6 left-align'>
                            <span class="muted"><?php echo CustomFuncs::invertDate($data->datePublication); ?></span>
                        </div>
                        <div class='col-xs-6 right-align'>
                            <span class="glyphicon glyphicon-eye-open"></span>&nbsp;<span class='muted'><?php echo $data->viewsCount->views; ?> </span>
                            &nbsp;&nbsp;
                            <span class="glyphicon glyphicon glyphicon-comment"></span>&nbsp;<span class='muted'><?php echo $data->commentsCount; ?> </span>
                        </div>
                    </div>

                </div>
            </small>
        </div>
</div>

<?php if ($index==2):?>
    </div>
<?php endif;?>

<div class="clearfix <?php if ($index == 1):?> visible-xs<?php endif;?>"></div>
<hr class="<?php if ($index == 1):?> visible-xs<?php endif;?>" />
