<?php
/* @var $this QuestionController */

use App\helpers\DateHelper;
use App\helpers\NumbersHelper;

/* @var $data Question */
?>




        <?php if ($data->title):?>
            <h4 class='left-align'><?php echo CHtml::link(CHtml::encode($data->title), Yii::app()->createUrl('question/view', ['id' => $data->id])); ?>
            <?php if (0 != $data->price && 1 == $data->payed):?>
            <span class="label label-primary">VIP</span>
            <?php endif; ?>
            </h4>
        <?php endif; ?>
		
       
        <p>
            <?php echo nl2br(mb_substr(CHtml::encode($data->questionText), 0, 240, 'utf-8')); ?>
            <?php if (strlen(CHtml::encode($data->questionText)) > 240) {
    echo '...';
} ?>
        </p>
        
		
        <hr/>
<small>
        <p>
        <?php if (!is_null($data->publishDate)) {
    echo DateHelper::niceDate($data->publishDate);
}?>&nbsp;&nbsp;    
            
        <?php if ($data->authorName):?>
        <span class="glyphicon glyphicon-user"></span>&nbsp;<?php echo CHtml::encode($data->authorName); ?>
        &nbsp;&nbsp;
        <?php endif; ?>
        
        <?php if ($data->town):?>
            <span class="glyphicon glyphicon-map-marker"></span>&nbsp;<?php echo CHtml::link(CHtml::encode($data->town->name), Yii::app()->createUrl('town/alias', [
                'name' => $data->town->alias,
                'countryAlias' => $data->town->country->alias,
                'regionAlias' => $data->town->region->alias,
                    ]), ['title' => 'Юристы в ' . CHtml::encode($data->town->name)]); ?>
            &nbsp;&nbsp;
        <?php endif; ?>
            
        <?php if (!$hideCategory && $data->categories):?>
            <?php foreach ($data->categories as $category):?>
            <span class="glyphicon glyphicon-folder-open"></span>&nbsp;&nbsp;<?php echo CHtml::link($category->name, Yii::app()->createUrl('questionCategory/alias', ['name' => CHtml::encode($category->alias)])); ?>
            &nbsp;&nbsp;
            <?php endforeach; ?>
        <?php endif; ?>    
        
        <?php if ($answersCount = $data->answersCount):?>
            <span class="glyphicon glyphicon-comment"></span>&nbsp;<?php echo $answersCount . '&nbsp;' . NumbersHelper::numForms($answersCount, 'ответ', 'ответа', 'ответов'); ?>
        <?php endif; ?>
        </p>
</small>