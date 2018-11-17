<?php
/**
 * @var QuestionCategory[] $neighbours
 * @var QuestionCategory[] $children
 * @var QuestionCategory $category
 */
?>
<ul id="left-menu-categories">
    <?php if (sizeof($neighbours) > 1): ?>
        <?php foreach ($neighbours as $neighbour): ?>
            <li>
                <?php if ($neighbour->id != $category->id): ?>
                    <?php echo CHtml::link($neighbour->name, Yii::app()->createUrl('questionCategory/alias', $neighbour->getUrl())); ?>
                <?php else: ?>
                    <?php echo $neighbour->name; ?>
                    <ul>
                        <?php foreach ($children as $child): ?>
                            <li>
                                <?php echo CHtml::link($child->name, Yii::app()->createUrl('questionCategory/alias', $child->getUrl())); ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    <?php else: ?>
        <?php foreach ($children as $child): ?>
            <li>
                <?php echo CHtml::link($child->name, Yii::app()->createUrl('questionCategory/alias', $child->getUrl())); ?>
            </li>
        <?php endforeach; ?>
    <?php endif; ?>
</ul>
