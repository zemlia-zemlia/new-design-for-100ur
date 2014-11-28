<?php
/* @var $this CategoryController */
/* @var $dataProvider CActiveDataProvider */

$this->setPageTitle("Блог" . " | ". Yii::app()->name);

$this->breadcrumbs=array(
	'Блог',
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
<h1>Блог</h1>

<table class="table table-condensed categories-list">
    <thead>
        <th>Категории</th>
        <th>Постов</th>
    </thead>
<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
        'summaryText'   =>  '',
        'pager'=>array('class'=>'GTLinkPager') //we use own pager with russian words
)); ?>
</table>
    
    </div>
    <div class="col-md-4">
        <div class="vert-margin40">
            <?php if(Yii::app()->user->checkAccess(User::ROLE_ROOT)):?>
                <?php echo CHtml::link('Создать категорию', Yii::app()->createUrl('blog/create'), array('class'=>'btn btn-primary btn-block')); ?>
            <?php endif;?>
        </div>
    
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
