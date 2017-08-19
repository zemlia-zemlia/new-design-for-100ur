<?php if(stristr($_SERVER['REQUEST_URI'], '/q/')):?>
<noindex>
<?php endif;?>
    
<div class="inside">
<?php

if(empty($answers) || sizeof($answers)==0) {
    echo "Не найдено ни одного ответа";
}
?>

<?php foreach($answers as $answer): ?>
    <div class="answer-item-panel">


<div class="row">
    <div class="col-xs-3">
        <div class="">
            <div class="row">
                <div class="col-md-4">
                    <?php if($answer['authorId'] && ($author=User::model()->cache(600)->findByPk($answer['authorId'])) instanceof User):?>
                        <img src="<?php echo $author->getAvatarUrl();?>" class="img-responsive img-bordered" alt="<?php echo CHtml::encode($author->name . ' ' . $author->lastName);?>" />
                    <?php endif;?>
                </div>
                <div class="col-md-8 text-muted">
                    <div class="answer-item-info">
                        <small>
                        <?php if($answer['answerTime']):?>
                        <span class="glyphicon glyphicon-calendar"></span> <?php echo CustomFuncs::niceDate($answer['answerTime'], false);?>
                        <br />
                        <?php endif;?>
                        <?php if($answer['authorId']):?>
                            <span class="glyphicon glyphicon-user"></span>
                            <?php echo $answer['authorLastName'] . ' ' . mb_substr($answer['authorName'], 0, 1, 'utf-8') . '.' . mb_substr($answer['authorName2'], 0, 1, 'utf-8') . '.';?> 
                            <?php if(floor((time() - strtotime($answer['lastActivity']))/60)<60):?>
                            <div><span class="glyphicon glyphicon-flash"></span> <span class="text-success">Сейчас на сайте</span></div>
                            <?php endif;?>
                        <?php endif;?>
                        </small>
                    </div>
                </div>
            </div>
        
        </div>
    </div>
    <div class="col-xs-9">
        <p>
            Вопрос:
            <?php echo CHtml::link(CHtml::encode($answer['questionTitle']), Yii::app()->createUrl('question/view',array('id'=>$answer['questionId'])));?>

        </p>
        <strong>Ответ:</strong> 
        <?php echo nl2br(mb_substr(CHtml::encode($answer['answerText']),0,250,'utf-8'));?>
        <?php if(mb_strlen($answer['answerText'])>250):?>
        ...
        <?php endif;?>
        <br />
    </div>
</div>


        
</div>
<?php endforeach;?>
</div> <!-- .inside -->

<?php if(stristr($_SERVER['REQUEST_URI'], '/q/')):?>
</noindex>
<?php endif;?>

<p class="right-align">
    <?php echo CHtml::link('Все вопросы', Yii::app()->createUrl('question'));?> &nbsp; &nbsp;
<?php echo CHtml::link('Задать свой вопрос', Yii::app()->createUrl('question/create'), array('class' => 'yellow-button arrow'));?>
</p>