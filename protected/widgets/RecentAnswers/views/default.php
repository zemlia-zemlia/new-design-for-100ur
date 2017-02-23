
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
    <?php echo CHtml::link(CHtml::encode($answer->question->title), Yii::app()->createUrl('question/view',array('id'=>$answer->question->id)));?>
        <?php if($answer->question->price!=0 && $answer->question->payed == 1):?>
            <span class="label label-primary">VIP</span>
            <?php endif;?>
</p>

<div class="row">
    <div class="col-md-4">
		<?php if($answer->author):?>
        <img src="<?php echo $answer->author->getAvatarUrl();?>" class="img-responsive img-bordered" />
		<?php endif;?>
    </div>
    <div class="col-md-8">
        <div class="answer-item-info">
            <?php if($answer->datetime):?>
            <img src="/pics/2017/icon_calendar_green.png" alt="" /> <?php echo CustomFuncs::niceDate($answer->datetime, false);?>
            <br />
            <?php endif;?>
            <?php if($answer->authorId):?>
                <img src="/pics/2017/icon_yurist_green.png" alt="" />
                    <?php if($answer->author->settings && $answer->author->settings->alias):?>
                        <?php echo CHtml::encode($answer->author->settings->alias);?>
                    <?php else:?>
                        <?php echo $answer->author->getShortName();?>
                    <?php endif;?>
            <?php endif;?>
        </div>
    </div>
</div>

<img src="/pics/2017/arrow_list_blue.png" alt="" /> 
<?php echo nl2br(mb_substr(CHtml::encode($answer->answerText),0,100,'utf-8'));?>...
<br />
        
</div>
<?php endforeach;?>
</div> <!-- .inside -->