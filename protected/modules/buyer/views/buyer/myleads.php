<?php

$this->setPageTitle("Часто задаваемые вопросы при работе с заявками (лидами) " . Yii::app()->name);
Yii::app()->clientScript->registerMetaTag("Что такое юридические лиды и как с ними работать. Часто задаваемые вопросы при работе с заявками (лидами)", 'description');

?>

<h2>Мои лиды</h2>

<?php if (sizeof(Yii::app()->user->getModel()->campaigns) == 0):?>
    <div class="alert alert-danger">
        <p>
            Для того, чтобы начать покупать лиды, Вам необходимо <?php echo CHtml::link('создать кампанию', Yii::app()->createUrl('campaign/create'));?> и дождаться ее проверки.<br />
            Цена лида будет определена модератором при одобрении кампании.<br />
            После этого Вы сможете пополнить баланс и получать лиды.
        </p>
    </div>
<?php endif;?>

<?php $this->widget('zii.widgets.CListView', array(
    'dataProvider'=>$dataProvider,
    'itemView'=>'_viewLead',
    'emptyText' =>  'Не найдено ни одного лида',
    'summaryText'=>'Показаны лиды с {start} до {end}, всего {count}',
    'pager'=>array('class'=>'GTLinkPager') //we use own pager with russian words
)); ?>

