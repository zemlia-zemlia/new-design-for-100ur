<?php

    $this->setPageTitle("Юридические компании России по городам");
    
    $this->breadcrumbs=array(
            'Юридические фирмы',
    );
    
?>

<?php
    $this->widget('zii.widgets.CBreadcrumbs', array(
        'homeLink'=>CHtml::link('Вопрос юристу',"/"),
        'separator'=>' / ',
        'links'=>$this->breadcrumbs,
     ));
?>

<div class="panel panel-default">
    <div class="panel-body">
        
        <h1>Юридические компании России по городам</h1>

        <div class="container-fluid">
            <?php
                $counter = 0;
            ?>
            <?php foreach($towns as $town):?>    
            <?php if($counter%3 == 0) echo "<div class='row'>";?>
                <div class="col-md-4 company-town-list-item">
                    <?php echo CHtml::link(CHtml::encode($town['name']), Yii::app()->createUrl('yurCompany/town', array('alias'=>$town['alias'])));?>
                    <p class="text-muted">
                        <?php echo $town['counter'] . ' ' . CustomFuncs::numForms($town['counter'], 'компания', 'компании', 'компаний');?>
                    </p>
                </div>
            <?php if($counter%3 == 2) echo "</div>";?>
            <?php
                $counter++;
            ?>
            <?php  endforeach;?>
            <?php if(($counter-1)%3 != 2) echo "</div>";?>
        </div>
    </div>
</div>