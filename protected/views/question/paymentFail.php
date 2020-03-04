<?php
    $this->setPageTitle('Платеж не совершен. ' . Yii::app()->name);
?>

<div class="panel gray-panel">
    <div class='panel-body'>
        <h1>Платеж не совершен</h1>
        <p>
            Не удалось совершить платеж. Мы постараемся разобраться, почему это произошло.
            
        </p>
        
        <p class="center-align">
            <?php
                if ($params['customerNumber']) {
                    echo CHtml::link('На страницу вопроса', Yii::app()->createUrl('question/view', ['id' => (int) $params['customerNumber']]), ['class' => 'btn btn-primary']);
                }
            ?>
        </p>
        
    </div>
</div>