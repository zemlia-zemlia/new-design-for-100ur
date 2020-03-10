<?php
$this->setPageTitle('Отзывы клиентов');
Yii::app()->clientScript->registerMetaTag('Отзывы', 'description');
$purifier = new CHtmlPurifier();
?>

<div class="container">
<?php if (count($testimonials)) :
    $i = 1;
    foreach ($testimonials as $t) :
        if ($i % 2 != 0) :?>
 <div class="row">

     <div class="col-lg-6">

             <h4 class="text-left">
                 <strong><?php echo CHtml::encode($t->author->name); ?></strong>

             </h4>
             <p class="vert-margin30">
                 <?php echo $purifier->purify($t->text); ?>
             </p>
             <?php if ($t->question): ?>
                 <p class="small">
                     Вопрос: <?php echo CHtml::link(CHtml::encode($t->question->title), Yii::app()->createUrl('question/view', ['id' => $t->question->id])); ?>
                 </p>
             <?php endif; ?>
             <span class="text-muted small right-align">
                        <?php echo DateHelper::niceDate($t->dateTime, false, false); ?>
                    </span>

     </div>



     <?php else  :?>


         <div class="col-lg-6">
             <h4 class="text-left">
                 <strong><?php echo CHtml::encode($t->author->name); ?></strong>

             </h4>
             <p class="vert-margin30">
                 <?php echo $purifier->purify($t->text); ?>
             </p>
             <?php if ($t->question): ?>
                 <p class="small">
                     Вопрос: <?php echo CHtml::link(CHtml::encode($t->question->title), Yii::app()->createUrl('question/view', ['id' => $t->question->id])); ?>
                 </p>
             <?php endif; ?>
             <span class="text-muted small right-align">
                        <?php echo DateHelper::niceDate($t->dateTime, false, false); ?>
                    </span>
         </div>
        </div>
         <?php endif; ?>
        <?php $i++;?>
<?php endforeach; ?>
<?php endif; ?>
</div>