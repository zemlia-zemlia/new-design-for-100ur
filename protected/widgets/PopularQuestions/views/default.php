<?php foreach ($questions as $question): ?>
    <div class="question">
        <div class="row">
            <div class="col-sm-2">
                <small class="text-muted">
                    <?php
                    $questionDatetime = (new DateTime($question['createDate']));
                    $nowDate = (new DateTime(date('Y-m-d 00:00:00')));
                    if ($questionDatetime >= $nowDate) {
                        echo 'сегодня в ' . $questionDatetime->format('H:i');
                    } else {
                        echo CustomFuncs::niceDate($question['createDate'], false, false);
                    }
                    ?>
                </small>
            </div>
            <div class="col-sm-8">
                <?php echo CHtml::link(CHtml::encode($question['title']), Yii::app()->createUrl('question/view', ['id' => $question['id']])); ?>
            </div>
            <div class="col-sm-2">
                <?php
                if ($question['answersCount'] == 1) {
                    echo "<span class='text-success'> <span class='glyphicon glyphicon-ok'></span> Есть ответ</span>";
                } elseif ($question['answersCount'] > 1) {
                    echo "<span class='text-success'> <span class='glyphicon glyphicon-ok'></span> " . $question['answersCount'] . ' ' . CustomFuncs::numForms($question['answersCount'], 'ответ', 'ответа', 'ответов') . "</span>";
                } elseif ($question['answersCount'] == 0) {
                    echo "<span class='text-muted'>Нет ответа</span>";
                }
                ?>
                <br/>
                <small>
                    <?php
                    echo $question['commentsCount'] . ' ' . CustomFuncs::numForms($question['commentsCount'], 'комментарий', 'комментария', 'комментариев') . "</span>";
                    ?>
                </small>
            </div>
        </div>
    </div>
<?php endforeach; ?>