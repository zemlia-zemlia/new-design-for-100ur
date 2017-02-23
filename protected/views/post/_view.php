<?php
    /*
     * Отображение поста в списке постов
     * $data - текущий пост
     */
?>

<div class="category-post">
    <div class="category-post-header">
        <h3>
            <?php echo CHtml::link(CHtml::encode($data->title), Yii::app()->createUrl('post/view',array('id'=>$data->id)));?>
        </h3>
        <div class="category-post-categories">
            <span class="glyphicon glyphicon-tags"></span> &nbsp;
            <?php foreach($data->categories as $category) {
                echo CHtml::link(CHtml::encode($category->title), Yii::app()->createUrl('blog/view',array('id'=>$category->id))) . "&nbsp;&nbsp; ";
            }

            ?>
        </div>
    </div>
    
    <div class="category-post-body">

        <div class="category-post-preview">
            <?php
                // очищаем текст поста от ненужных тегов перед выводом в браузер
                $purifier = new Purifier();
                echo $purifier->purify($data->preview) . ' ' . CHtml::link('читать весь пост', Yii::app()->createUrl('post/view',array('id'=>$data->id))); 
            ?>
        </div>
        
        <div class="post-stats">
            <i class="glyphicon glyphicon-eye-open"></i>&nbsp;<?php echo $data->viewsCount->views;?>
        </div>
        
    </div>
    <div class="clearfix"></div>
</div>