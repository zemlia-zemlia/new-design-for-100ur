<?php
/**
 * @var QuestionCategory[]
 * @var QuestionCategory[] $children
 * @var QuestionCategory   $category
 */
?>
<ul id="left-menu">
    <?php if (sizeof($children) > 0): ?>
        <?php foreach ($children as $child): ?>
            <li>
                <?php echo CHtml::link(CustomFuncs::mb_ucfirst($child->name), Yii::app()->createUrl('questionCategory/alias', $child->getUrl())); ?>
            </li>
        <?php endforeach; ?>
    <?php elseif (sizeof($neighbours) > 0): ?>
        <?php foreach ($neighbours as $neighbour): ?>
            <li>
                <?php if ($neighbour->id != $category->id): ?>
                    <?php echo CHtml::link(CustomFuncs::mb_ucfirst($neighbour->name), Yii::app()->createUrl('questionCategory/alias', $neighbour->getUrl())); ?>
                <?php else: ?>
                    <span>
                    <?php echo CustomFuncs::mb_ucfirst($neighbour->name); ?>
                    </span>
                    <ul>
                        <?php foreach ($children as $child): ?>
                            <li>
                                <?php echo CHtml::link(CustomFuncs::mb_ucfirst($child->name), Yii::app()->createUrl('questionCategory/alias', $child->getUrl())); ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    <?php endif; ?>
</ul>
