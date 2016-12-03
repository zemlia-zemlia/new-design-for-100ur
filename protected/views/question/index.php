<?php
/* @var $this QuestionController */
/* @var $dataProvider CActiveDataProvider */

//Yii::app()->clientScript->registerLinkTag("alternate","application/rss+xml","http://".$_SERVER['SERVER_NAME'].Yii::app()->createUrl('question/rss'));
Yii::app()->clientScript->registerLinkTag("canonical",NULL,"http://".$_SERVER['SERVER_NAME'].Yii::app()->createUrl('question'));

$pageTitle = "Вопросы юристам ";

$this->setPageTitle($pageTitle . Yii::app()->name);
?>


<div class="flat-panel">

        <h1 class="header-block-light-grey vert-margin20"><?php echo $pageTitle;?></h1>

        <div class="inside">
        <?php foreach($questions as $question):?>
            <div class="row question-list-item  <?php if($question->payed == 1):?> vip-question<?endif;?>">
                <div class="col-sm-9">
                    <p style="font-size:1.1em;">
                        <?php if($question->payed == 1){
                            echo "<span class='label label-primary'><abbr title='Вопрос с гарантией получения ответов'>VIP</abbr></span>";
                        }
                        ?>
                        <?php echo CHtml::link($question->title, Yii::app()->createUrl('question/view', array('id'=>$question->id)));?>
                    </p>
                </div>
                
                <div class="col-sm-3">
                
                <?php if($question->answersCount == 1) {
                    echo "<img src='/pics/2017/icon_checkmark.png' alt='Есть ответ' /> <span class='text-success'>Есть ответ</span>";
                } elseif($question->answersCount>1) {
                    echo "<img src='/pics/2017/icon_checkmark.png' alt='Есть ответ' /> <span class='text-success'>" . $question->answersCount . ' ' . CustomFuncs::numForms($question->answersCount, 'ответ', 'ответа', 'ответов') . "</span>";
                } elseif($question->answersCount == 0) {
                    echo "<span class='text-muted'>Нет ответа</span>";
                }
                ?>
                </span>
            </div>
            </div>
        <?php endforeach;?>
        </div>
</div>

