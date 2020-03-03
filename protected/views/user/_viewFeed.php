<div class="feed-item">
    <div class="row">
        <?php
        // Определяем, куда будет вести ссылка
        switch ($data['type']) {
            case Comment::TYPE_ANSWER: default:
                $route = "question/view";
                break;
        }
        ?>
        <div class="col-sm-3">
            <?php
            echo "<strong class='green'>+" . ($data['counter']) . ' ' . NumbersHelper::numForms($data['counter'], 'комментарий', 'комментария', 'комментариев') . "</strong>";
            ?>
        </div>
        <div class="col-sm-7">
            <p>
                <strong><?php echo CHtml::link(Chtml::encode($data['title']), Yii::app()->createUrl($route, ['id' => $data['id']])); ?></strong>
            </p>
        </div>
        <div class="col-sm-2 right-align">
            <?php echo CHtml::link('Смотреть', Yii::app()->createUrl($route, ['id' => $data['id']]), ['class' => 'btn btn-info btn-block']); ?>
            <?php echo CHtml::link('Скрыть', '#', ['class' => 'btn btn-default hide-comment btn-block btn-xs', 'data-id' => $data['id']]); ?>
        </div>
    </div>
    <hr />
</div>