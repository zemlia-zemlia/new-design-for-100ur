<?php
/* @var $this QuestionController */
/* @var $dataProvider CActiveDataProvider */

$this->setPageTitle("Вопросы и ответы.". Yii::app()->name);
Yii::app()->clientScript->registerScriptFile('/js/question.js');


$this->breadcrumbs=array(
	'Вопросы и ответы',
);
$this->widget('zii.widgets.CBreadcrumbs', array(
    'homeLink'=>CHtml::link('CRM',"/"),
    'separator'=>' / ',
    'links'=>$this->breadcrumbs,
 ));
?>
<div class="vert-margin30">
<h1>Вопросы.
    <?php if($nocat):?>
        без категории
    <?php endif;?>
    <?php if($notown):?>
        без города
    <?php endif;?>


<?php if(Yii::app()->user->checkAccess(User::ROLE_ROOT)):?>

    <?php if(!is_null($status)):?>
        <?php echo Question::getStatusName($status); ?>
        <?php if($status == Question::STATUS_MODERATED):?>
            <?php echo CHtml::link('Опубликовать все одобренные', Yii::app()->createUrl('question/publish'),array('class'=>'btn btn-success'));?>
        <?php endif;?>

        </h3>
    <?php endif;?>
        
    </div>
<?php endif;?>
</h1>
    
<table class="table table-bordered table-hover table-striped">
    <thead>
    <tr>
        <th>Вопрос</th>
        <?php if(Yii::app()->user->checkAccess(User::ROLE_ROOT)):?>
            <th>Категория</th>
            <th>Автор</th>
        <?php endif;?>
    </tr>
    </thead>
<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'      =>  '_view',
        'emptyText'     =>  'Не найдено ни одного вопроса',
        'summaryText'   =>  'Показаны вопросы с {start} до {end}, всего {count}',
        'pager'         =>  array('class'=>'GTLinkPager') //we use own pager with russian words

)); ?>
</table>