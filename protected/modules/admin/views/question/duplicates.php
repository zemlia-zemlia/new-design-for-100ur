<?php
/* @var $this QuestionController */
/* @var $dataProvider CActiveDataProvider */

$this->setPageTitle("Дубли вопросов. ". Yii::app()->name);
Yii::app()->clientScript->registerScriptFile('/js/question.js');
Yii::app()->clientScript->registerScriptFile('/js/admin/question.js');


$this->breadcrumbs=array(
	'Дубли вопросов',
);
$this->widget('zii.widgets.CBreadcrumbs', array(
    'homeLink'=>CHtml::link('100 юристов',"/admin/"),
    'separator'=>' / ',
    'links'=>$this->breadcrumbs,
 ));
?>

<table class="table table-bordered table-hover table-striped">
    <thead>
    <tr>
        <th>Вопрос</th>
        <?php if(Yii::app()->user->checkAccess(User::ROLE_ROOT)):?>
            <th>Категория</th>
        <?php endif;?>   
        <?php if(Yii::app()->user->checkAccess(User::ROLE_EDITOR)):?>
            <th>Автор</th>
        <?php endif;?>
    </tr>
    </thead>
<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'      =>  '_view',
        'emptyText'     =>  'Не найдено ни одного вопроса',
        'summaryText'   =>  'Показаны вопросы с {start} до {end}, всего {count}',
        'pager'         =>  array('class'=>'GTLinkPager'), //we use own pager with russian words
        'viewData'      =>  array(
            'allDirections' =>  $allDirections,
            'nocat'         =>  $nocat,
        ),
)); ?>
</table>
