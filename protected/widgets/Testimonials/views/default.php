<?php
$purifier = new CHtmlPurifier();
?>

<?php if (sizeof($testimonials) > 0): ?>

    <h2 class="vert-margin30">Свежие отзывы</h2>
    <?php foreach ($testimonials as $index => $testimonial): ?>
        <?php if ($index % 3 == 0): ?>
            <div class="row vert-margin30">
        <?php endif; ?>

        <div class="col-sm-4">
            <p>
                <?php echo CHtml::encode($testimonial->author->name); ?>
                <span class="text-muted small">
                        <?php echo CustomFuncs::niceDate($testimonial->dateTime, false, false); ?>
                    </span>
            </p>
            <p class="vert-margin30">
                <?php echo $purifier->purify($testimonial->text); ?>
            </p>
            <?php if ($testimonial->question): ?>
                <p class="small">
                    Вопрос: <?php echo CHtml::link(CHtml::encode($testimonial->question->title), Yii::app()->createUrl('question/view', ['id' => $testimonial->question->id])); ?>
                </p>
            <?php endif; ?>
        </div>
        <?php if ($index % 3 == 2): ?>
            </div>
        <?php endif; ?>

    <?php endforeach; ?>

    <?php if ($index % 3 != 2): ?>
        </div>
    <?php endif; ?>

<?php endif; ?>