<?php
/* @var $this CategoryController */
?>

<div class='panel'>
    <div class='panel-body'>
        
        <div class="category-post-header">
        <h3>
            <?php echo CHtml::link(CHtml::encode($data->title), Yii::app()->createUrl('post/view',array('id'=>$data->id)));?>
        </h3>
        </div>
        
        <div class="category-post-preview">
            <?php
                // очищаем текст поста от ненужных тегов перед выводом в браузер
                $purifier = new Purifier();
                echo $purifier->purify($data->preview) . ' ' . CHtml::link('читать продолжение', Yii::app()->createUrl('post/view',array('id'=>$data->id))); 
            ?>
        </div>
        
        <div class="post-stats">
            <div class='row'>
                <div class='col-md-6 col-sm-6'>
                    <span class="muted"><?php echo CustomFuncs::invertDate($data->datePublication);?></span>
                </div>
                <div class='col-md-6 col-sm-6 right-align'>
                    <img src='/pics/2015/icon_eye.png' alt='' />&nbsp;<span class='muted'><?php echo $data->viewsCount->views;?> <?php echo CustomFuncs::numForms($data->viewsCount->views, 'просмотр', "просмотра", "просмотров");?></span>
                </div>
            </div>
            
        </div>
        
    </div>
</div>