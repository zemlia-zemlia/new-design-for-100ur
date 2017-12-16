<div class="feed-item">
    <div class="row">
        <?php
        // Определяем, куда будет вести ссылка
        switch ($data['type']) {
            case Comment::TYPE_ANSWER: default;
                $route = "question/view";
                break;
        }
        ?>
        <div class="col-sm-3">
            <?php
            echo "<strong class='green'>+" . ($data['counter']) . ' ' . CustomFuncs::numForms($data['counter'], 'комментарий', 'комментария', 'комментариев') . "</strong>";
            ?>
        </div>
        <div class="col-sm-7">
            <p>
                <strong><?php echo CHtml::link(Chtml::encode($data['title']), Yii::app()->createUrl($route, ['id' => $data['id']])); ?></strong>
            </p>
        </div>
        <div class="col-sm-2 right-align">
            <?php echo CHtml::link('Скрыть', '#', ['class' => 'btn btn-default hide-comment', 'data-id' => $data['id']]); ?>
        </div>
    </div>
    <hr />
</div>