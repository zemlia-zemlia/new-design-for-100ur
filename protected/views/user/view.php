<?php
/* @var $this UserController */
/* @var $model User */


$userDisplayName = CHtml::encode($model->name . ' ' . $model->lastName);

$this->breadcrumbs=array(
        'Юристы и Адвокаты' =>  array('/yurist'),
	$userDisplayName,
);
if($model->settings) {
    $userStatusName = $model->settings->getStatusName() . ' ';
} else {
    $userStatusName = '';
}
$this->setPageTitle($userStatusName . $userDisplayName . '. Город ' . $model->town->name . '. ' . Yii::app()->name);
Yii::app()->clientScript->registerMetaTag("Пользователи портала 100 Юристов. " . $userStatusName . $userDisplayName . '. Город ' . $model->town->name . '. ', "Description");
        
$this->widget('zii.widgets.CBreadcrumbs', array(
    'homeLink'=>CHtml::link('100 Юристов',"/"),
    'separator'=>' / ',
    'links'=>$this->breadcrumbs,
 ));
            
?>

<div itemscope itemtype="http://schema.org/Person">   
        <h1 class="vert-margin30">
            <span itemprop="jobTitle"><?php echo $userStatusName;?></span> <span itemprop="name"><?php echo CHtml::encode($model->name . ' ' . $model->name2 . ' ' . $model->lastName);?></span>
        </h1>
        
        <div class="container-fluid vert-margin30">
            <div class="row">
                <div class="col-sm-3 center-align">
                    <p>
                        <img src="<?php echo $model->getAvatarUrl();?>" class="gray-panel img-bordered" itemprop="image" />
                    </p>    
                    <?php echo CHtml::link('Задать вопрос', Yii::app()->createUrl('question/create'), array('class'=>'btn btn-info'));?>

                </div>
                <div class="col-sm-9">
                    
                    <?php if($model->settings->hello):?>
                    <div class="alert alert-info">
                        <?php echo CHtml::encode($model->settings->hello);?>
                    </div>
                    <?php endif;?>
                    
                    <?php if($model->role == User::ROLE_JURIST):?>
                    <div class='flat-panel inside vert-margin20'>
                    <div class="row">
                        <div class="col-sm-6 center-align">
                            <p>Дано ответов</p>
                            <?php
                                $answersCountInt = $model->answersCount;
                                $answersCount = str_pad((string)$answersCountInt,(strlen($answersCountInt)>4)?strlen($answersCountInt):4, '0',STR_PAD_LEFT);
                                $numbers = str_split($answersCount);
                                
                                $karmaCount = str_pad((string)$model->karma, (strlen($model->karma)>3)?strlen($model->karma):3, '0',STR_PAD_LEFT);;
                                $numbersKarma = str_split($karmaCount);
                            ?>
                            
                            <p class="kpi-counter">
                                <?php foreach($numbers as $num):?><span><?php echo $num;?></span><?php endforeach;?><br />
                            </p>
                                
                        </div>
                        <div class="col-sm-6 center-align">
                            <p><abbr title="Количество благодарностей за полезный ответ">Карма</abbr></p>
                            <p class="kpi-counter">
                                <?php foreach($numbersKarma as $num):?><span><?php echo $num;?></span><?php endforeach;?><br />
                            </p>
                        </div>
                    </div>
                    </div>
                    <?php endif;?>
                    
                    
                    
                    <?php if($model->settings->description):?>
                    <div class='flat-panel inside vert-margin20'>
                        <h3 class="left-align">О себе</h3>
                        <p><?php echo CHtml::encode($model->settings->description);?></p>
                    </div>
                    <?php endif;?>
            
                    
                    <div class='flat-panel inside vert-margin20'>
                    <h3 class="left-align">Контакты</h3>
                    
                    <p>
                        <strong>Город:</strong> <?php echo $model->town->name;?>
                    </p>
                    
                    <?php if($model->settings->phoneVisible):?>
                    <p>
                        <span class="hidden-block-container">
                            <strong>Телефон:</strong> 
                                <span class="hidden-block">
                                <?php echo $model->settings->phoneVisible;?>
                                </span>
                                <span class="hidden-block-trigger">
                                    <a href="#" class="btn btn-default btn-xs">Показать</a>
                                </span>
                        </span>
                    </p> 
                    <?php endif;?>
                    
                    <?php if($model->settings->emailVisible):?>
                    <p>
                        <span class="hidden-block-container">
                        <strong>Email:</strong> 
                            <span class="hidden-block">
                                <?php echo CHtml::encode($model->settings->emailVisible);?>
                            </span>
                            <span class="hidden-block-trigger">
                                <a href="#" class="btn btn-default btn-xs">Показать</a>
                            </span>
                        </span>
                    </p>
                    <?php endif;?>
                    
                    <?php if($model->settings->site):?>
                    <p>
                        <strong>Сайт:</strong> <?php echo CHtml::encode($model->settings->site);?>
                    </p>
                    <?php endif;?>
                   
                    </div>
                    
                    <?php if($model->settings->education):?>
                    <div class='flat-panel inside vert-margin20'>
                        <h3 class="left-align">Образование</h3> 
                        <p>
                            <?php if($model->settings->education) echo $model->settings->education . ', ';?>
                            <?php if($model->settings->vuz) echo 'ВУЗ: ' . $model->settings->vuz . ', ';?>
                            <?php if($model->settings->vuzTownId) echo '(' . $model->settings->vuzTown->name . '), ';?>
                            <?php if($model->settings->educationYear) echo 'год окончания: ' . $model->settings->educationYear . '.';?>
                        </p>
                    </div> 
                    <?php endif;?>
                    
                    <?php if($model->categories):?>
                        <div class='flat-panel inside vert-margin20'>
                            <h3 class="left-align">Специализации</h3>

                            <?php foreach ($model->categories as $cat): ?>
                            <span class="yurist-directions-item"><?php echo $cat->name; ?></span>
                            <?php endforeach;?>
                        </div>        
                    <?php endif;?>
                    
                    
                    <?php if($model->settings->priceConsult  > 0 || $model->settings->priceDoc  > 0):?>
                        <div class='flat-panel inside vert-margin20'>
                            <h3 class="left-align">Платные услуги</h3>
                            <?php if($model->settings->priceConsult  > 0):?>
                                <p>Консультация от <?php echo $model->settings->priceConsult;?> руб.</p>
                            <?php endif;?>
                            <?php if($model->settings->priceDoc  > 0):?>
                                <p>Составление документа от <?php echo $model->settings->priceDoc;?> руб.</p>
                            <?php endif;?>
                        </div>
                    <?php endif;?>
                    
                </div>
            </div>
        </div>
</div>        


<div class="vert-margin30">
<?php if(sizeof($questions)):?>        
<h2 class="header-block-light-grey">Последние ответы</h2>
<?php endif;?>
        
<?php foreach($questions as $question):?>
    <div class="row question-list-item <?php if($question['payed'] == 1):?> vip-question<?endif;?>">
        <div class="col-sm-12">
            <p style="font-size:1.1em;">
                <?php if($question['payed'] == 1){
                    echo "<span class='label label-primary'><abbr title='Вопрос с гарантией получения ответов'>VIP</abbr></span>";
                }
                ?>
                <?php echo CHtml::link($question['title'], Yii::app()->createUrl('question/view', array('id'=>$question['id'])));?>
            </p>
        </div>
    </div>
<?php endforeach;?>
</div>

<?php 
    if(Yii::app()->user->role == User::ROLE_ROOT) {
        echo CHtml::link('Смотреть статистику ответов по месяцам', Yii::app()->createUrl('user/stats', array('userId'=>$model->id)), array('class'=>'btn btn-block btn-xs btn-default'));
    }
?>


    