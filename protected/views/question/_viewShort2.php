<p>
    <small><?php use App\helpers\DateHelper;

        echo CHtml::link(CHtml::encode($data->title), Yii::app()->createUrl('question/view', ['id' => $data->id])); ?>
    <br />
    <?php if ($data->answers):?>
        <?php foreach ($data->answers as $answer):?>
        <p>
            <?php echo nl2br(mb_substr(CHtml::encode($answer->answerText), 0, 150, 'utf-8')); ?>...
            <br />
            <?php if ($answer->datetime):?>
            <span class="glyphicon glyphicon-calendar"></span> <?php echo DateHelper::niceDate($answer->datetime, false); ?>
            <?php endif; ?>
            <?php if ($answer->authorId):?>
                <span class="glyphicon glyphicon-user"></span> 
                    <?php echo $answer->author->getShortName(); ?>
            <?php endif; ?>
        </p>    
        <?php endforeach; ?>
    <?php endif; ?>
    </small>
    
</p>
<hr />