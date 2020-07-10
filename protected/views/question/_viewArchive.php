<?php use App\helpers\NumbersHelper;
use App\helpers\StringHelper;



?>

 <div class="archive-questions__item">
    <?= CHtml::link(StringHelper::mb_ucfirst($data->title, 'utf-8'),
        Yii::app()->createUrl('question/view', ['id' => $data->id]), ['class' => 'archive-questions__title']); ?>
    <?php if ($data->answersCount > 0) : ?>
        <a href=""	class="archive-questions__btn"><?= NumbersHelper::numForms($data->answersCount, 'ответ', 'ответа', 'ответов', true)  ?> </a>
    <?php else: ?>
        <div class="archive-questions__no-answer">Нет ответа</div>
    <?php endif; ?>
</div>
