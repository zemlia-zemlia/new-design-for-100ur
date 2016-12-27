<?php
/* @var $this ContactController */
/* @var $dataProvider CActiveDataProvider */

$this->pageTitle = "Лиды. " . Yii::app()->name;

Yii::app()->clientScript->registerScriptFile('/js/lead.js');
        
$this->breadcrumbs=array(
	'Лиды',
);

?>
<div  class="vert-margin30">
    <h1>Лиды</h1>
    <p class="center-align">
        Кампания: <?php echo $campaign->region->name . ' ' . $campaign->town->name;?>
        <?php if($status!==false) {
            $statuses = Lead100::getLeadStatusesArray();
            echo ', Статус: ' . $statuses[$status];
        }
        ?>
    </p>
</div>


<div class="panel panel-default">
    <div class="panel-body">
        
        <?php if($dataProvider->totalItemCount):?>
        <table class="table table-bordered table-hover table-striped">
            <thead>
            <tr>
                <th>Текст лида</th>
                <th>Управление</th>
            </tr>
            </thead>
        <?php $this->widget('zii.widgets.CListView', array(
                'dataProvider'=>$dataProvider,
                'itemView'=>'_viewLead',
                'emptyText' =>  'Не найдено ни одного лида',
                'summaryText'=>'Показаны лиды с {start} до {end}, всего {count}',
                'pager'=>array('class'=>'GTLinkPager') //we use own pager with russian words
        )); ?>
        </table>
        <?php else:?>
        <p>Не найдено ни одного лида</p>
        <?php endif;?>
        
    </div>
</div>