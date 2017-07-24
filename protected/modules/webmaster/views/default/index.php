<?php
/* @var $this ContactController */
/* @var $dataProvider CActiveDataProvider */

$this->pageTitle = "Личный кабинет вебмастера. " . Yii::app()->name;


?>
<h1>Кабинет вебмастера</h1>

<div class="vert-margin40">
    <h2>Мои лиды</h2>
    <table class="table table-bordered table-hover table-striped">
    <?php $this->widget('zii.widgets.CListView', array(
            'dataProvider'  =>  $dataProvider,
            'itemView'      =>  'application.modules.webmaster.views.lead._view',
            'emptyText'     =>  'Не найдено ни одного лида',
            'summaryText'   =>  'Показаны лиды с {start} до {end}, всего {count}',
            'pager'         =>  array('class'=>'GTLinkPager') //we use own pager with russian words
    )); ?>
    </table>
</div>

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
