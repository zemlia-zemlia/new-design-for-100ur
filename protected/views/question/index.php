<?php
/* @var $this QuestionController */
/* @var $dataProvider CActiveDataProvider */

Yii::app()->clientScript->registerLinkTag("canonical",NULL, Yii::app()->createUrl('question'));

$pageTitle = "Последние советы юристов бесплатно онлайн ";

$this->setPageTitle($pageTitle . Yii::app()->name);

Yii::app()->clientScript->registerMetaTag("Советы юристов по всем отраслям права задайте свой вопрос и получите ответ в течении 15 минут", 'description');
?>


<div class="flat-panel">

        <h1 class="header-block-light-grey vert-margin20">Последние вопросы юристам</h1>

        <div class="inside">
        <?php foreach($questions as $question):?>
            <div class="row question-list-item  <?php if($question->payed == 1):?> vip-question<?endif;?>">
                <div class="col-sm-10">
                    <p style="font-size:0.9em;">
                        <?php if($question->payed == 1){
                            echo "<span class='label label-primary'><abbr title='Вопрос с гарантией получения ответов'>VIP</abbr></span>";
                        }
                        ?>
                        <?php echo CHtml::link($question->title, Yii::app()->createUrl('question/view', array('id'=>$question->id)));?>
                    </p>
                </div>
                
                <div class="col-sm-2 text-center">
                    <small>
                    <?php if($question->answersCount == 1) {
                        echo "<span class='text-success'> <span class='glyphicon glyphicon-ok'></span> Есть ответ</span>";
                    } elseif($question->answersCount>1) {
                        echo "<span class='text-success'> <span class='glyphicon glyphicon-ok'></span> " . $question->answersCount . ' ' . CustomFuncs::numForms($question->answersCount, 'ответ', 'ответа', 'ответов') . "</span>";
                    } elseif($question->answersCount == 0) {
                        echo "<span class='text-muted'>Нет ответа</span>";
                    }
                    ?>
                    </small>
            </div>
            </div>
        <?php endforeach;?>
        </div>
       
</div>

<div class="flat-panel">
    <div class="inside">
        <?php $monthsNames = CustomFuncs::getMonthsNames();?>
        
        <h3>Архив вопросов</h3>
        <?php foreach ($datesArray as $year=>$months):?>
        <h4><?php echo $year;?></h4>
        <div class="row">
            <?php foreach ($months as $month):?>
            <div class="col-md-2 text-center">
                <?php echo CHtml::link($monthsNames[$month], Yii::app()->createUrl('question/archive', array('date' => $year . '-' . $month)));?> 
            </div>
            <?php endforeach;?>
        </div>            
        <?php endforeach;?>
    </div>
</div>

