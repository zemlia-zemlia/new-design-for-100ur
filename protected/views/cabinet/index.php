<?php
$this->setPageTitle("Кабинет покупателя лидов. ". Yii::app()->name);

?>

<h1 class="vert-margin20">Мои лиды</h1>

<?php if(sizeof($currentUser->campaigns) == 0):?>
<div class="alert alert-danger">
    <p>
        Для того, чтобы начать покупать лиды, Вам необходимо <?php echo CHtml::link('создать кампанию', Yii::app()->createUrl('campaign/create'));?> и дождаться ее проверки.<br />
        Цена лида будет определена модератором при одобрении кампании.<br />
        После этого Вы сможете пополнить баланс и получать лиды.
    </p>
</div>

<?php endif;?>
   
        
<table class="table table-bordered table-hover table-striped">
    <thead>
    <tr>
        <th>Лид</th>
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

<?php 
if(!$showInactive) {
    echo CHtml::link('Показать неактивные', $this->createUrl('?show_inactive=true'));
}
?>

