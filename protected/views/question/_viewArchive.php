<div class="row question-list-item  <?php if($data->payed == 1):?> vip-question<?endif;?>">
    <div class="col-sm-10">
        <p style="font-size:0.9em;">
            <?php if($data->payed == 1){
                echo "<span class='label label-primary'><abbr title='Вопрос с гарантией получения ответов'>VIP</abbr></span>";
            }
            ?>
            <?php echo CHtml::link(CHtml::encode(CustomFuncs::mb_ucfirst($data->title, 'utf-8')), Yii::app()->createUrl('question/view', array('id'=>$data->id)));?>
        </p>
    </div>

    <div class="col-sm-2 text-center">
        <small>
        <?php if($data->answersCount == 1) {
            echo "<span class='text-success'> <span class='glyphicon glyphicon-ok'></span> Есть ответ</span>";
        } elseif($data->answersCount>1) {
            echo "<span class='text-success'> <span class='glyphicon glyphicon-ok'></span> " . $data->answersCount . ' ' . CustomFuncs::numForms($data->answersCount, 'ответ', 'ответа', 'ответов') . "</span>";
        } elseif($data->answersCount == 0) {
            echo "<span class='text-muted'>Нет ответа</span>";
        }
        ?>
        </small>
    </div>
</div>