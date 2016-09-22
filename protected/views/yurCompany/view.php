<?php
    $this->setPageTitle($company->name . ' ' . CHtml::encode($company->town->name) . '. Отзывы, контакты');

    Yii::app()->clientScript->registerMetaTag(CHtml::encode($company->name . ' ' . $company->town->name . ', адрес, отзывы'), "Description");

        
    $this->breadcrumbs=array(
        'Юридические фирмы' =>  array('/company'),
        CHtml::encode($company->town->name) =>  array('yurCompany/town', 'alias'=>$company->town->alias),
        $company->name,
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

        
        <h1 class="vert-margin30"><?php echo CHtml::encode($company->name); ?></h1>
        
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-4">
                    <?php if($company->logo):?>
                    <?php echo CHtml::image($company->getPhotoUrl(), CHtml::encode($company->name), array('class'=>'img-responsive'));?>
                    <?php endif;?>
                </div>
                <div class="col-md-8">
                    <?php if($company->town):?>
                    <p><strong>Город:</strong> <?php echo CHtml::encode($company->town->name);?></p>
                    <?php endif;?>
                    
                    <?php if($company->address):?>
                    <p><strong>Адрес:</strong> <?php echo CHtml::encode($company->address);?></p>
                    <?php endif;?>
                    
                    <?php if($company->metro):?>
                    <p><strong>Метро:</strong> <?php echo CHtml::encode($company->metro);?></p>
                    <?php endif;?>
                    
                    <?php if($company->yearFound):?>
                    <p><strong>Год основания:</strong> <?php echo CHtml::encode($company->yearFound);?></p>
                    <?php endif;?>
                    
                    <?php if($company->website):?>
                    <p><strong>Сайт:</strong> <?php echo CHtml::encode($company->website);?></p>
                    <?php endif;?>
                    
                    <p><strong>Телефон:</strong> 
                    <?php for($p=1;$p<=3;$p++){
                        $phoneField = 'phone' . $p;
                        if($company->{$phoneField}) {
                            echo $company->{$phoneField} . '&nbsp;&nbsp;';
                        }
                    }
                    ?>
                    </p>
                    
                    <?php if($company->yurName):?>
                    <p><strong>Юридическое название:</strong> <?php echo CHtml::encode($company->yurName);?></p>
                    <?php endif;?>
                    
                    <?php if($company->yurAddress):?>
                    <p><strong>Юридический адрес:</strong> <?php echo CHtml::encode($company->yurAddress);?></p>
                    <?php endif;?>
                </div>
            </div>
        </div>
        
        <?php echo $company->description;?>
    </div>
</div>