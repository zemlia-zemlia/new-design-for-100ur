<?php if(stristr($_SERVER['REQUEST_URI'], '/q/')):?>
<noindex>
<?php endif;?>
    
<div class="inside">
<?php

$index=0;
if(empty($answers) || sizeof($answers)==0) {
    echo "Не найдено ни одного ответа";
}
?>

<?php foreach($answers as $answer): ?>
    
<?php 
    $author=User::model()->cache(600)->findByPk($answer['authorId']);
?>
    
<?php if($index%2 == 0) :?>
    <div class="row">
<?php endif;?>
    
    <div class="col-md-6">
        <p>
            <?php echo CHtml::link(CHtml::encode($answer['questionTitle']), Yii::app()->createUrl('question/view',array('id'=>$answer['questionId'])));?>
        
        
        <?php if($answer['authorId']):?>
            <br />
            <strong>
                <small>
                    <?php echo $author->settings->getStatusName();?>
                    <?php echo $answer['authorLastName'] . ' ' . mb_substr($answer['authorName'], 0, 1, 'utf-8') . '.' . mb_substr($answer['authorName2'], 0, 1, 'utf-8') . '.';?> 
                    <?php if(floor((time() - strtotime($answer['lastActivity']))/60)<60):?>
                        <span class="glyphicon glyphicon-flash"></span> <span class="text-success">Сейчас на сайте</span>
                    <?php endif;?>
                </small>
            </strong>
        <?php endif;?>
        </p>
        
        <div class="row">
            <div class="col-xs-4 col-sm-3">

                <?php if($answer['authorId'] && ($author=User::model()->cache(600)->findByPk($answer['authorId'])) instanceof User):?>
                    <img src="<?php echo $author->getAvatarUrl();?>" class="img-responsive" alt="<?php echo CHtml::encode($author->name . ' ' . $author->lastName);?>" />
                <?php endif;?>

            </div>
            <div class="col-xs-8 col-sm-9">

                <?php echo nl2br(mb_substr(CHtml::encode($answer['answerText']),0,150,'utf-8'));?>
                <?php if(mb_strlen($answer['answerText'])>150):?>
                ...
                <?php endif;?>
                <br />
            </div>    
        </div>
    </div>

    

    <?php if($index%2 == 1) :?>
        </div>
        <?php if($index != 5):?>
        <hr />
        <?php endif;?>
    <?php endif;?>
        
    <?php $index++;?>
    
<?php endforeach;?>
        
    <?php if($index%2 == 1) :?>
        </div> <!-- .row -->
    <?php endif;?>
</div> <!-- .inside -->

<?php if(stristr($_SERVER['REQUEST_URI'], '/q/')):?>
</noindex>
<?php endif;?>

<p class="right-align">
    <?php echo CHtml::link('Все вопросы', Yii::app()->createUrl('/question/index'));?> &nbsp; &nbsp;
<?php echo CHtml::link('Задать свой вопрос', Yii::app()->createUrl('question/create'), array('class' => 'yellow-button arrow'));?>
</p>