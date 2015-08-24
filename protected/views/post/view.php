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

<div class='panel'>
    <div class="panel-body">
        
   
        <div class="category-post-header">

            
            <?php if($model->authorId == Yii::app()->user->id || Yii::app()->user->checkAccess('moderator')):?>
            <div>
                <i class="glyphicon glyphicon-edit"></i> <?php echo CHtml::link('Редактировать пост', Yii::app()->createUrl('post/update', array('id'=>$model->id)));?>
                &nbsp;&nbsp; 
                <i class="glyphicon glyphicon-remove"></i> <?php echo CHtml::link('Удалить пост', Yii::app()->createUrl('post/delete', array('id'=>$model->id)), array('style'=>'color:#ff0000;'));?>
            </div>
            <?php endif; ?>
        </div>
        
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


        <div class="post-stats">
            <div class='row'>
                <div class='col-md-6 col-sm-6'>
                    <span class="muted"><?php echo CustomFuncs::invertDate($model->datePublication);?></span>
                </div>
                <div class='col-md-6 col-sm-6 right-align'>
                    <img src='/pics/2015/icon_eye.png' alt='' />&nbsp;<span class='muted'><?php echo $model->viewsCount->views;?> <?php echo CustomFuncs::numForms($model->viewsCount->views, 'просмотр', "просмотра", "просмотров");?></span>
                </div>
            </div>
        </div>
     
    </div>
</div>                       
            
       
<h3>При поддержке</h3>

<div class="panel">
    <div class="panel-body">
        <div class="row">
            <div class="col-md-6 col-sm-6 center-align">
                <img class="img-responsive center-block" alt="При поддержке правительства РФ" src="/pics/pravitelstvo.png">
                <p class="center-align">Правительство РФ
                </p>
            </div>

            <div class="col-md-6 col-sm-6 center-align"> 
                <img class="img-responsive center-block" alt="При поддержке Министерства Юстиции" src="/pics/minyust.png"> 
                <p class="center-align">Министерство Юстиции</p>
            </div>
        </div>
    </div>
</div>
        
