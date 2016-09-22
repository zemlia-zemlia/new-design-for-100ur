
<h4>Последние ответы</h4>
<?php

if(empty($answers) || sizeof($answers)==0) {
    echo "Не найдено ни одного ответа";
}
?>

<?php foreach($answers as $answer): ?>

<small><?php echo CHtml::link(CHtml::encode($answer->question->title), Yii::app()->createUrl('question/view',array('id'=>$answer->question->id)));?>
<br />

<div class="row">
    <div class="col-xs-3">
        <img src="<?php echo $answer->author->getAvatarUrl();?>" class="img-responsive" />
    </div>
    <div class="col-xs-9">
        <?php if($answer->datetime):?>
        <span class="glyphicon glyphicon-calendar"></span> <?php echo CustomFuncs::niceDate($answer->datetime, false);?>
        <br />
        <?php endif;?>
        <?php if($answer->authorId):?>
            <span class="glyphicon glyphicon-user"></span> 
                <?php if($answer->author->settings && $answer->author->settings->alias):?>
                    <?php echo CHtml::encode($answer->author->settings->alias);?>
                <?php else:?>
                    <?php echo $answer->author->getShortName();?>
                <?php endif;?>
        <?php endif;?>
        </small>
    </div>
</div>

<?php echo nl2br(mb_substr(CHtml::encode($answer->answerText),0,150,'utf-8'));?>...
<br />
        
<hr />
<?php endforeach;?>