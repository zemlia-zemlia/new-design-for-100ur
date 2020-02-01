<?php
/**
 * Если пользователь запрашивает звонок из продажного региона, показываем ему форму запроса звонка (лид),
 * если из непродажного - форму создания вопроса
 */
$this->setPageTitle("Заказать звонок ". Yii::app()->name);
?>

<div class='flat-panel vert-margin30'>
    <div class='inside'>
        
        <?php if ($isRegionPayed):?>
            <h1 class="">Запрос звонка юриста</h1>
            <?php echo $this->renderPartial('_formCall', array(
                'model'         =>  $lead
            )); ?>
        <?php else:?>
            <h1 class="">Задать вопрос юристу</h1>
            <p>
                В Вашем городе в данный момент не осуществляются консультации по телефону. <br />
                Вы можете задать вопрос и получить письменный ответ на сайте.
            </p>
            <?php echo $this->renderPartial('_form', array(
                'model'         =>  $question,
            )); ?>
        <?php endif;?>
    </div>
</div>