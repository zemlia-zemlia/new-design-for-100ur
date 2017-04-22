
<h3 class="header-block header-block-grey header-icon-answers">Ответы</h3>
<div class="header-block-blue-arrow" style="width:50px;"></div>

<div class="inside">
<?php

if(empty($answers) || sizeof($answers)==0) {
    echo "Не найдено ни одного ответа";
}
?>

<?php foreach($answers as $answer): ?>
    <div class="answer-item-panel">
<p>
    <?php echo CHtml::link(CHtml::encode($answer['questionTitle']), Yii::app()->createUrl('question/view',array('id'=>$answer['questionId'])));?>
        <?php if($answer['questionPrice']!=0 && $answer['questionPayed'] == 1):?>
            <span class="label label-primary">VIP</span>
            <?php endif;?>
</p>

<div class="row">
    <div class="col-md-4">
        <?php if($answer['authorId'] && ($author=User::model()->cache(600)->findByPk($answer['authorId'])) instanceof User):?>
            <img src="<?php echo $author->getAvatarUrl();?>" class="img-responsive img-bordered" alt="<?php echo CHtml::encode($author->name . ' ' . $author->lastName);?>" />
        <?php endif;?>
    </div>
    <div class="col-md-8">
        <div class="answer-item-info">
            <?php if($answer['answerTime']):?>
            <span class="glyphicon glyphicon-calendar"></span> <?php echo CustomFuncs::niceDate($answer['answerTime'], false);?>
            <br />
            <?php endif;?>
            <?php if($answer['authorId']):?>
                <span class="glyphicon glyphicon-user"></span>
                <?php echo $answer['authorLastName'] . ' ' . mb_substr($answer['authorName'], 0, 1, 'utf-8') . '.' . mb_substr($answer['authorName2'], 0, 1, 'utf-8') . '.';?> 
                <?php if(floor((time() - strtotime($answer['lastActivity']))/60)<30):?>
                <div><span class="glyphicon glyphicon-flash"></span> <span class="text-success">Сейчас на сайте</span></div>
                <?php endif;?>
            <?php endif;?>
        </div>
    </div>
</div>

<img src="/pics/2017/arrow_list_blue.png" alt="Ответ" /> 
<?php echo nl2br(mb_substr(CHtml::encode($answer['answerText']),0,100,'utf-8'));?>...
<br />
        
</div>
<?php endforeach;?>
</div> <!-- .inside -->