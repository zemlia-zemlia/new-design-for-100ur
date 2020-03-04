<p class="random-question-town"><strong>
    <?php echo $question['authorName']; ?> <br />
    <?php echo Town::getName($question['townId']); ?>
    </strong>
</p>
<p class="random-question-text">
    <?php echo nl2br(mb_substr(CHtml::encode($question['questionText']), 0, 300, 'utf-8')); ?>
    <?php if (strlen($question['questionText']) > 300) {
    echo '...';
}?>
</p>
<?php echo CHtml::link('Ответить', Yii::app()->createUrl('answer/create', ['questionId' => $question['id']]), ['class' => 'btn btn-success btn-block', 'id' => 'random-question-link']); ?>
<a id="random-question-refresh" class="btn btn-warning btn-block">Другой вопрос</a>

<script>
    $("#random-question-refresh").on('click',function(){
        $.get('/question/getRandom/', function(data){
            if(data == 'NULL') {
                return;
            }
            var jsonData = JSON.parse(data);
            var code = jsonData.code;
            if(code == 0) {
                var question = jsonData.question;
                var name = jsonData.name;
                var town = jsonData.town;
                var qId = jsonData.id;
                
                $(".random-question-town strong").html(name  + '<br />' + town);
                $(".random-question-text").html(question);
                $("#random-question-link").prop('href','/answer/create/questionId/' + qId + '/');
            }
        })
        
        return false;
    })
</script>