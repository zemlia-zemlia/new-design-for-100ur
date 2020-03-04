<?php foreach ($topCategories as $index => $cat): ?>

    <?php if (0 == $index % 3): ?>
        <div class="row">
    <?php endif; ?>
    <div class="col-sm-4 vert-margin20">
        <?php echo CHtml::link(CHtml::encode($cat['name']), Yii::app()->createUrl('questionCategory/alias', ['name' => CHtml::encode($cat['alias'])])); ?>
    </div>
    <?php if (2 == $index % 3): ?>
        </div>
    <?php endif; ?>
<?php endforeach; ?>