<?php
$purifier = new CHtmlPurifier();
?>

<?php if (sizeof($testimonials) > 0): ?>

    <h2 class="vert-margin30">Свежие отзывы</h2>
    <?php foreach ($testimonials as $index => $testimonial): ?>
        <?php if (0 == $index % 3): ?>
            <div class="row vert-margin30">
        <?php endif; ?>

        <div class="col-sm-4">
            <h4 class="text-left">
                <strong><?php echo CHtml::encode($testimonial->author->name); ?></strong>
 
            </h4>
            <p class="vert-margin30">
                <?php echo $purifier->purify($testimonial->text); ?>
            </p>
            <?php if ($testimonial->question): ?>
                <p class="small">
                    Вопрос: <?php echo CHtml::link(CHtml::encode($testimonial->question->title), Yii::app()->createUrl('question/view', ['id' => $testimonial->question->id])); ?>
                </p>
            <?php endif; ?>
                            <span class="text-muted small right-align">
                        <?php echo DateHelper::niceDate($testimonial->dateTime, false, false); ?>
                    </span>
        </div>
        <?php if (2 == $index % 3): ?>
            </div>
        <?php endif; ?>

    <?php endforeach; ?>

    <?php if (2 != $index % 3): ?>
        </div>
    <?php endif; ?>

<?php endif; ?>