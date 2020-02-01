<?php
$this->pageTitle = 'Статистика пользователя ' . $model->name . ' ' . $model->lastName . '. ' . Yii::app()->name;


$monthsArray = array(
        '1'     =>  'Январь',
        '2'     =>  'Февраль',
        '3'     =>  'Март',
        '4'     =>  'Апрель',
        '5'     =>  'Май',
        '6'     =>  'Июнь',
        '7'     =>  'Июль',
        '8'     =>  'Август',
        '9'     =>  'Сентябрь',
        '10'    =>  'Октябрь',
        '11'    =>  'Ноябрь',
        '12'    =>  'Декабрь',
    );
?>


<?php if (Yii::app()->user->checkAccess(User::ROLE_MANAGER) && ($model->role == User::ROLE_JURIST || $model->role == User::ROLE_OPERATOR)):?>    
    <h2>Статистика сотрудника <?php echo $model->getShortName();?></h2>
    
    <?php if (!$print):?>
    <div class="vert-margin30 right-align">
        <?php echo CHtml::link('Печать', Yii::app()->createUrl('user/stats', array('id'=>$model->id, 'print'=>1)), array('target'=>'_blank'));?>
    </div>
    <?php else:?>
    <div class="vert-margin30 right-align">
        <a onclick="window.print()">Печать</a>    
    </div>
    <?php endif;?>
    
    <?php if ($model->role == User::ROLE_JURIST) {
    $this->renderPartial('_statJurist', array(
                'monthsArray'   =>  $monthsArray,
                'leadsArray'    =>  $leadsArray,
                'channelsArray' =>  $channelsArray,
                'meetingsArray' =>  $meetingsArray,
                'agreementsArray'   =>  $agreementsArray,
                'transactionsArray' =>  $transactionsArray,
            ));
} elseif ($model->role == User::ROLE_OPERATOR) {
    $this->renderPartial('_statOperator', array(
                'monthsArray'   =>  $monthsArray,
                'leadsArray'    =>  $leadsArray,
                'channelsArray' =>  $channelsArray,
                'meetingsArray' =>  $meetingsArray,
                'agreementsArray'   =>  $agreementsArray,
                'meetingsCurrentArray'  =>  $meetingsCurrentArray,
            ));
}
    ?>
<?php endif;?>