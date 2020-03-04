<?php if (stristr($_SERVER['REQUEST_URI'], '/q/')):?>
<noindex>
<?php endif; ?>

<ul id="left-menu">
    
<?php foreach ($topCategories as $cat):?>
    
    <?php
    if ((isset($_GET['name']) && $_GET['name'] == $cat['alias'])) {
        $active = true;
        // определим, показывать ли текущий элемент ссылкой
        if ($_GET['name'] == $cat['alias']) {
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

        <?php echo (true == $showLink) ? CHtml::link(CHtml::encode($cat['name']), ['questionCategory/alias', 'name' => CHtml::encode($cat['alias'])]) : CHtml::encode($cat['name']); ?>
        
    </li>

<?php endforeach; ?>
</ul>

<?php if (stristr($_SERVER['REQUEST_URI'], '/q/')):?>
</noindex>
<?php endif; ?>