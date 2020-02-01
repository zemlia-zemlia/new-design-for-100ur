<?php
/* @var $this UserStatusRequestController */
/* @var $dataProvider CActiveDataProvider */
$this->pageTitle = "Заявки на вывод средств. " . Yii::app()->name;


$this->breadcrumbs = array(
    'Заявки на вывод средств',
);

Yii::app()->clientScript->registerScriptFile('/js/admin/partnerTransaction.js');


?>
<div>
    <h3>Заявки на вывод средств</h3>
</div>

<div class="box">
    <div class="box-body">
        <table class="table table-bordered">
            <tr>
                <th>Пользователь</th>
                <th>Текущий баланс</th>
                <th>Сумма вывода</th>
                <th>куда вывести</th>
                <th>Управление</th>
            </tr>
            <?php $this->widget('zii.widgets.CListView', array(
                'dataProvider' => $dataProvider,
                'itemView' => '_view',
                'emptyText' => 'Не найдено ни одной заявки',
                'summaryText' => 'Показаны заявки с {start} до {end}, всего {count}',
                'pager' => array('class' => 'GTLinkPager'), //we use own pager with russian words
            )); ?>
        </table>

    </div>
</div>