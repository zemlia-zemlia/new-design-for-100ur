<?php
/* @var $this ContactController */
/* @var $dataProvider CActiveDataProvider */

$this->pageTitle = "Личный кабинет вебмастера. " . Yii::app()->name;


?>




    <h2>Кабинет вебмастера
        <?php if (sizeof(Leadsource::getSourcesArrayByUser(Yii::app()->user->id))>0):?>
            <?php echo CHtml::link('Добавить новый лид', Yii::app()->createUrl('/webmaster/lead/create'), array('class' => 'btn btn-primary'));?>
        <?php endif;?>
    </h2>
    <?php $this->widget('zii.widgets.CListView', array(
            'dataProvider'  =>  $dataProvider,
            'itemView'      =>  'application.modules.webmaster.views.lead._view',
            'emptyText'     =>  'Не найдено ни одного лида',
            'summaryText'   =>  'Показаны лиды с {start} до {end}, всего {count}',
            'pager'         =>  array('class'=>'GTLinkPager') //we use own pager with russian words
    )); ?>


<!-- 
<div class="vert-margin40">
    <h2>Мои вопросы</h2>
    <table class="table table-bordered table-hover table-striped">
    <?php $this->widget('zii.widgets.CListView', array(
            'dataProvider'  =>  $questionsDataProvider,
            'itemView'      =>  'application.modules.webmaster.views.question._view',
            'emptyText'     =>  'Не найдено ни одного вопроса',
            'summaryText'   =>'Показаны лиды с {start} до {end}, всего {count}',
            'pager'         =>array('class'=>'GTLinkPager') //we use own pager with russian words
    )); ?>
    </table>
</div>

-->
