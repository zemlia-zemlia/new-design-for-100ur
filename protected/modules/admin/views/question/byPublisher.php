<?php

    $this->setPageTitle("Вопросы, одобренные пользователем " . CHtml::encode($publisher->name . ' ' . $publisher->lastName) . Yii::app()->name);
?>


<h1>Вопросы, одобренные пользователем <?php echo CHtml::encode($publisher->name . ' ' . $publisher->lastName);?></h1>

<table class="table table-bordered table-hover table-striped">
    <thead>
    <tr>
        <th>Вопрос</th>
        <?php if (Yii::app()->user->checkAccess(User::ROLE_ROOT)):?>
            <th>Категория</th>
            <th>Автор</th>
            <th>Статус</th>
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