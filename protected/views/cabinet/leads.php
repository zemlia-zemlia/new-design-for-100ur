<?php
/* @var $this ContactController */
/* @var $dataProvider CActiveDataProvider */

$this->pageTitle = "Лиды. " . Yii::app()->name;

Yii::app()->clientScript->registerScriptFile('/js/lead.js');
        
$this->breadcrumbs=array(
    'Кабинет'   =>  array('/cabinet'),
    'Лиды',
);

$this->widget('zii.widgets.CBreadcrumbs', array(
    'homeLink'=>CHtml::link('100 юристов',"/"),
    'separator'=>' / ',
    'links'=>$this->breadcrumbs,
 ));

?>
<div  class="">
    <h1>Лиды <?php echo $campaign->region->name . ' ' . $campaign->town->name;?></h1>
</div>

<ul class="nav nav-tabs">
  <li role="presentation" <?php if(!$status):?>class="active"<?php endif;?>><?php echo CHtml::link('Все', Yii::app()->createUrl('/cabinet/leads', array('campaign'=>$campaign->id)));?></li>
  <li role="presentation" <?php if($status == Lead100::LEAD_STATUS_SENT):?>class="active"<?php endif;?>><?php echo CHtml::link('Новые', Yii::app()->createUrl('/cabinet/leads', array('campaign'=>$campaign->id, 'status'=>Lead100::LEAD_STATUS_SENT)));?></li>
  <li role="presentation" <?php if($status == Lead100::LEAD_STATUS_NABRAK):?>class="active"<?php endif;?>><?php echo CHtml::link('На отбраковке', Yii::app()->createUrl('/cabinet/leads', array('campaign'=>$campaign->id, 'status'=>Lead100::LEAD_STATUS_NABRAK)));?></li>
  <li role="presentation" <?php if($status == Lead100::LEAD_STATUS_BRAK):?>class="active"<?php endif;?>><?php echo CHtml::link('Брак', Yii::app()->createUrl('/cabinet/leads', array('campaign'=>$campaign->id, 'status'=>Lead100::LEAD_STATUS_BRAK)));?></li>
  <li role="presentation" <?php if($status == Lead100::LEAD_STATUS_RETURN):?>class="active"<?php endif;?>><?php echo CHtml::link('Возврат', Yii::app()->createUrl('/cabinet/leads', array('campaign'=>$campaign->id, 'status'=>Lead100::LEAD_STATUS_RETURN)));?></li>
</ul>

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