<?php

use App\helpers\DateHelper;

$this->setPageTitle('Отзывы наших клиентов - "100 Юристов"');
Yii::app()->clientScript->registerMetaTag('Отзывы наших клиентов о нашей работе - Юридический портал "100 Юристов"', 'description');
$purifier = new CHtmlPurifier();
?>


<h1>Отзывы наших клиентов</h1>

<div class="container">
    <?php if (count($testimonials)) :
        $i = 1;
        foreach ($testimonials as $testimonial) :
            if (0 != $i % 2) :?>
                <div class="row vert-margin40">
                <div class="col-lg-6">
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

            <?php else  : ?>

                <div class="col-lg-6">
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
                </div>
            <?php endif; ?>
            <?php ++$i; ?>
        <?php endforeach; ?>
    <?php endif; ?>
</div>