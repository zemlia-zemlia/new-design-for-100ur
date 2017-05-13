<div class="annoying-widget flat-panel">
    
    <img src="/pics/2017/call.jpg" alt="Консультация юриста по телефону" class="center-block">
    
    <div class="inside">
        <?php if($showAlways === true || array_key_exists($currentTown->id, $payedTownsIds) || array_key_exists($currentTown->regionId, $payedRegionsIds)):?>
            <small>
            <p>Наши юристы и адвокаты готовы бесплатно ответить на ваш вопрос прямо сейчас по многоканальному номеру</p>    
            </small>
            <div class="annoying-phone">8-800-500-61-85</div>
            <?php foreach($payedTowns as $town):?>
                <?php $townsNames[] = CHtml::encode($town->name);?> 
            <?php endforeach;?>
            
            <?php if(sizeof($townsNames)):?>
            <small>
                <p>Города: 
                    <?php echo implode(', ', $townsNames);?>
                </p>
            </small>
            <?php endif;?>
                
            <?php foreach($payedRegions as $region):?>
                <?php $regionsNames[] = CHtml::encode($region->name);?> 
            <?php endforeach;?>
                
            <?php if(sizeof($regionsNames)):?>
                <small>
                <p>Регионы: 
                    <?php echo implode(', ', $regionsNames);?>
                </p>
                </small>
            <?php endif;?>
        <?php else:?>
            <p>Наши юристы и адвокаты готовы бесплатно ответить на ваш вопрос прямо сейчас</p>
            <?php echo CHtml::link('Задать вопрос', Yii::app()->createUrl('question/create'), array('class' => 'btn btn-success btn-block'));?>
        <?php endif;?>
            
        <?php echo CHtml::link('Для юристов', "#collapseNewYurist", array(
            'class'         => 'btn btn-default btn-block', 
            'role'          => "button", 
            'data-toggle'   => "collapse", 
            'aria-expanded' => "false", 
            'aria-controls' => "collapseNewYurist",
            ));?>

        <div class="collapse" id="collapseNewYurist">
            <small>
            <p>
                Уважаемый юрист, если Вы хотите консультировать граждан из Вашего города, 
                <strong><?php echo CHtml::link('Зарегистрируйтесь', Yii::app()->createUrl('user/create', array('role' => User::ROLE_JURIST)));?></strong>
            </p>
            </small>
        </div>
    </div>
</div>

