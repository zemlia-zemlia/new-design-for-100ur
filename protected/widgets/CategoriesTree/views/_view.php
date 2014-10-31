<li>
    <?php echo CHtml::link(CHtml::encode($data->name), array('questionCategory/alias', 'name'=>CHtml::encode($data->alias))); ?>
    <?php if(sizeof($data->children)):?>
        <ul>
            <?php foreach($data->children as $child):?>
                <li><?php echo CHtml::link(CHtml::encode($child->name), array('questionCategory/alias', 'name'=>CHtml::encode($child->alias))); ?></li>
            <?php endforeach;?>
        </ul>
    <?php endif;?>
</li>