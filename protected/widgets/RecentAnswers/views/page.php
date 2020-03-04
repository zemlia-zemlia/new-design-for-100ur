<?php if (stristr($_SERVER['REQUEST_URI'], '/q/')): ?>
<noindex>
    <?php endif; ?>

    <div class="inside">
        <?php
        $index = 0;
        if (empty($answers) || 0 == sizeof($answers)) {
            echo 'Не найдено ни одного ответа';
        }
        ?>

        <?php foreach ($answers as $answer): ?>

            <?php
            $author = User::model()->cache(600)->findByPk($answer['authorId']);
            ?>

            <?php if (0 == $index % 2) : ?>
                <div class="row">
            <?php endif; ?>

            <div class="col-md-6">
                <p>
                    <?php echo CHtml::link(CHtml::encode(StringHelper::mb_ucfirst($answer['questionTitle'], 'utf-8')), Yii::app()->createUrl('question/view', ['id' => $answer['questionId']])); ?>

                </p>

                <div class="row answer-item-author">
                    <div class="col-xs-4 col-sm-3">
                        <p>
                            <?php if ($answer['authorId'] && ($author = User::model()->cache(600)->findByPk($answer['authorId'])) instanceof User): ?>
                                <img src="<?php echo $author->getAvatarUrl(); ?>" class="img-responsive"
                                     alt="<?php echo CHtml::encode($author->name . ' ' . $author->lastName); ?>"/>
                            <?php endif; ?>
                        </p>
                    </div>
                    <div class="col-xs-8 col-sm-9">
                        <?php if ($answer['authorId']): ?>
                            <p>
                                <strong>
                                    <small>

                                        <?php echo $answer['authorLastName'] . ' ' . mb_substr($answer['authorName'], 0, 1, 'utf-8') . '.' . mb_substr($answer['authorName2'], 0, 1, 'utf-8') . '.'; ?>
                                        <?php if ($author->settings): ?>
                                            <em class="text-muted"><?php echo $author->settings->getStatusName(); ?></em>
                                        <?php endif; ?>
                                        <?php if (floor((time() - strtotime($answer['lastActivity'])) / 60) < 60): ?>
                                            <span class="glyphicon glyphicon-flash"></span> <span class="text-success">Сейчас на сайте</span>
                                        <?php endif; ?>
                                    </small>
                                </strong>
                            </p>

                        <?php endif; ?>
                        <div class="hidden-xs">
                            <?php echo CHtml::encode(mb_substr($answer['answerText'], 0, 150, 'utf-8')); ?>
                            <?php if (mb_strlen($answer['answerText'], 'utf-8') > 150): ?>
                                ...
                            <?php endif; ?>
                        </div>
                        <br/>
                    </div>
                </div>
                <div class="visible-xs-block">
                    <?php echo mb_substr(CHtml::encode($answer['answerText']), 0, 150, 'utf-8'); ?>
                    <?php if (mb_strlen($answer['answerText']) > 150): ?>
                        ...
                    <?php endif; ?>
                </div>
            </div>


            <?php if (0 == $index % 2): ?>
                <hr class="visible-xs-block"/>
            <?php endif; ?>

            <?php if (1 == $index % 2) : ?>
                </div>

                <?php if (5 != $index): ?>
                    <hr/>
                <?php endif; ?>
            <?php endif; ?>

            <?php ++$index; ?>

        <?php endforeach; ?>

        <?php if (1 == $index % 2) : ?>
    </div> <!-- .row -->
<?php endif; ?>
    </div> <!-- .inside -->

    <?php if (stristr($_SERVER['REQUEST_URI'], '/q/')): ?>
</noindex>
<?php endif; ?>

<p class="right-align">
    <?php echo CHtml::link('Все вопросы', Yii::app()->createUrl('/question/index'), ['class' => 'text-muted']); ?> &nbsp; &nbsp;
    <?php echo CHtml::link('Задать свой вопрос юристам', Yii::app()->createUrl('question/create'), ['class' => 'yellow-button arrow']); ?>
</p>