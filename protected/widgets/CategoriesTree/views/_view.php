<?php
    if ((isset($_GET['name']) && $_GET['name'] == $data->alias) || hasActiveChild($data, $_GET['name'])) {
        $active = true;
        // определим, показывать ли текущий элемент ссылкой
        if ($_GET['name'] == $data->alias) {
            $showLink = false;
        } else {
            $showLink = true;
        }
    } else {
        $active = false;
        $showLink = true;
    }
    
    
?>

<li
    <?php
        if ($active) {
            echo " class='active'";
        }
    ?>

    >
    
    <?php echo ($showLink==true)?CHtml::link(CHtml::encode($data->name), array('questionCategory/alias', 'name'=>CHtml::encode($data->alias))):CHtml::encode($data->name); ?>
    <?php /*
    <?php if((sizeof($data->children) && isset($_GET['name']) && $_GET['name'] == $data->alias) || hasActiveChild($data, $_GET['name'])):?>
        <ul>
            <?php foreach($data->children as $child):?>
                <li><?php echo ($child->alias != $_GET['name'])?CHtml::link(CHtml::encode($child->name), array('questionCategory/alias', 'name'=>CHtml::encode($child->alias))):CHtml::encode($child->name); ?></li>
            <?php endforeach;?>
        </ul>
    <?php endif;?>
     */?>
</li>
