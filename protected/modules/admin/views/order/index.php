<?php
$this->setPageTitle("Заказы документов" . '. ' . Yii::app()->name);
?>

<h1>Заказы документов</h1>

<table class="table table-bordered">
    <tr>
        <th>номер</th>
        <th>город</th>
        <th>дата</th>
        <th>тип</th>
        <th>юрист</th>
        <th>комм.</th>
    </tr>
    <?php $this->widget('zii.widgets.CListView', array(
            'dataProvider'  =>  $ordersDataProvider,
            'itemView'      =>  '_view',
            'emptyText'     =>  'Не найдено ни одного заказа',
            'summaryText'   =>  'Показаны заказы с {start} до {end}, всего {count}',
            'pager'         =>  array('class'=>'GTLinkPager'), //we use own pager with russian words
        )); 
    ?>
</table>
