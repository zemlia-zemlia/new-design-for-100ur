<?php foreach ($topCategories as $index => $cat): ?>

    <?php if ($index % 3 == 0): ?>
        <div class="row">
    <?php endif; ?>
    <div class="col-sm-4 vert-margin20">
        <?php echo CHtml::link(CHtml::encode($cat['name']), Yii::app()->createUrl('questionCategory/alias', ['name' => CHtml::encode($cat['alias'])])); ?>
    </div>
    <?php if ($index % 3 == 2): ?>
        </div>
    <?php endif; ?>
<?php endforeach; ?>