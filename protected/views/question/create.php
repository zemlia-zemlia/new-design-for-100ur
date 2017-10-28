<?php
/* @var $this QuestionController */
/* @var $model Question */

$this->setPageTitle("Задать вопрос юристу бесплатно онлайн. ". Yii::app()->name);

Yii::app()->ClientScript->registerScriptFile('/js/jquery.maskedinput.min.js', CClientScript::POS_END);
Yii::app()->clientScript->registerMetaTag("Задать вопрос юристу онлайн бесплатно без телефона и регистрации круглосуточно по всей России.", 'description');

$this->breadcrumbs=array(
	'Вопросы и ответы'=>array('index'),
	'Новый вопрос',
);

?>

        <?php
            $questionsCountInt = Question::getCount()*2;
            $questionsCount = str_pad((string)$questionsCountInt,6, '0',STR_PAD_LEFT);
            $numbers = str_split($questionsCount);
            $answersCount = round($questionsCountInt*1.684);
            $numbersAnswers = str_split($answersCount);
            
            $yuristsCountRow = Yii::app()->db->createCommand()
                    ->select('COUNT(*) counter')
                    ->from('{{user}}')
                    ->where('role=:role', array(':role' => User::ROLE_JURIST))
                    ->queryRow();
            $yuristsCount = round($yuristsCountRow['counter']*5.314);
        ?>
     
        <div class="counters-wrapper counters-wrapper-border vert-margin20">
            <div class="row">
                <div class="col-sm-3 center-align counter-green">
                    <div class="counter-number">
                        <span class="glyphicon glyphicon-bullhorn"></span> <?php echo $questionsCountInt;?>
                    </div>
                    <div class="counter-description">
                        вопросов задано
                    </div>
                </div>
                <div class="col-sm-3 center-align counter-yellow">
                    <div class="counter-number">
                        <span class="glyphicon glyphicon-comment"></span> <?php echo $answersCount;?>
                    </div>
                    <div class="counter-description">
                        ответов получено
                    </div>
                </div>
                <div class="col-sm-3 center-align counter-green">
                    <div class="counter-number">
                        <span class="glyphicon glyphicon-education"></span> <?php echo $yuristsCount;?>
                    </div>
                    <div class="counter-description">
                        юристов на сайте
                    </div>
                </div>
                <div class="col-sm-3 center-align counter-yellow">
                    <div class="counter-number">
                        <span class="glyphicon glyphicon-thumbs-up"></span>
                    </div>
                    <div class="counter-description">
                        нас рекомендуют
                    </div>
                </div>
            </div>
        </div>

<div class='flat-panel vert-margin30'>
    <div class='inside'>
        <h1 class="">Задать вопрос юристу</h1>

        <?php echo $this->renderPartial('_form', array(
            'model'         =>  $model,
            'allDirections' =>  $allDirections,
            'categoryId'    =>  $categoryId,
            'pay'           =>  $pay,
        )); ?>
    </div>
</div>