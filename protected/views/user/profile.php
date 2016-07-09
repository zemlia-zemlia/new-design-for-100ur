<?php
/* @var $this UserController */
/* @var $model User */

$this->breadcrumbs=array(
	'Личный кабинет',
);

$this->setPageTitle("Личный кабинет пользователя. ". Yii::app()->name);
        
$this->widget('zii.widgets.CBreadcrumbs', array(
    'homeLink'=>CHtml::link('100 юристов',"/"),
    'separator'=>' / ',
    'links'=>$this->breadcrumbs,
 ));
            
?>

<div class="panel panel-default">
    <div class="panel-body">
        <h1>Личный кабинет пользователя</h1>
        <h2>Мои вопросы</h2>
        
        <?php $this->widget('zii.widgets.CListView', array(
            'dataProvider'  =>  $questionsDataProvider,
            'itemView'      =>  'application.views.question._view',
            'viewData'      =>  array(
                'hideCategory'  =>  false,
            ),
            'emptyText'     =>  'Не найдено ни одного вопроса',
            'ajaxUpdate'    =>  false,
            'summaryText'   =>  '',
            'pager'         =>  array('class'=>'GTLinkPager') //we use own pager with russian words
    )); ?>
        
    </div>
</div>
