<?php
$this->setPageTitle("Кабинет покупателя лидов. ". Yii::app()->name);

$this->breadcrumbs=array(
	'Кабинет покупателя лидов'
);

?>

<?php
    $this->widget('zii.widgets.CBreadcrumbs', array(
        'homeLink'=>CHtml::link('100 юристов',"/"),
        'separator'=>' / ',
        'links'=>$this->breadcrumbs,
     ));
?>

<h1>Кабинет покупателя лидов</h1>


<div class='panel'>
    <div class='panel-body'>
        <h2>Мои кампании</h2> 
        
        
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Регион</th>
                    <th><span class="glyphicon glyphicon-time"></span></th>
                    <th>%&nbsp;брака</th>
                    <th>Лимит</th>
                    <th>Цена</th>
                    <th>Баланс</th>
                    <th>Отправлено</th>
                </tr>
            </thead>    
        <?php $this->widget('zii.widgets.CListView', array(
                'dataProvider'=>$dataProvider,
                'itemView'=>'application.views.campaign._view',
                'emptyText'     =>  'Не найдено ни одной кампании',
                'ajaxUpdate'    =>  false,
                'summaryText'   =>  '',
                'pager'         =>  array('class'=>'GTLinkPager') //we use own pager with russian words
        )); ?>
        </table>

        <?php 
        if(!$showInactive) {
            echo CHtml::link('Показать неактивные', $this->createUrl('?show_inactive=true'));
        }
        ?>
        
        
    </div>
</div>
