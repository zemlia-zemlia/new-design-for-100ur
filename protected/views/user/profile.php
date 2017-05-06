<?php
/* @var $this UserController */
/* @var $model User */

$this->breadcrumbs=array(
	'Личный кабинет',
);

$this->setPageTitle("Личный кабинет пользователя. ". Yii::app()->name);
        
$this->widget('zii.widgets.CBreadcrumbs', array(
    'homeLink'=>CHtml::link('100 юристов',"/"),
    'separator'=>' / ',
    'links'=>$this->breadcrumbs,
 ));
            
?>

       <div class="vert-margin30">
            <h1 class='vert-margin30'>
                <?php
                    echo CHtml::encode($user->name . ' ' . $user->name2 . ' ' . $user->lastName);
                ?>
            </h1>
            
            <div class="container-fluid">
            <div class="row">
                <div class="col-sm-3 center-align">
                    <p>
                        <img src="<?php echo $user->getAvatarUrl();?>" class='img-bordered' />
                    </p>  
                                            
                </div>
                <div class="col-sm-9">
                    <div class='flat-panel inside vert-margin20'>
                        <div class="row">
                            <div class="col-sm-6 center-align">
                                <p>Дано ответов</p>
                                <?php
                                    $answersCountInt = $user->answersCount;
                                    $answersCount = str_pad((string)$answersCountInt,(strlen($answersCountInt)>4)?strlen($answersCountInt):4, '0',STR_PAD_LEFT);
                                    $numbers = str_split($answersCount);

                                    $karmaCount = str_pad((string)$user->karma, (strlen($user->karma)>3)?strlen($user->karma):3, '0',STR_PAD_LEFT);;
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
                    <?php if(Yii::app()->user->role == User::ROLE_JURIST):?>
                    
                        <?php if($user->settings->hello):?>
                        <div class="alert alert-info">
                            <?php echo CHtml::encode($user->settings->hello);?>
                        </div>
                        <?php endif;?>
                        
                        
                        <?php if($lastRequest && $lastRequest['isVerified'] == 0):?>
                            <div class='alert alert-success'>
                            <p>Активна заявка на подтверждение статуса <?php echo YuristSettings::getStatusNameByCode($lastRequest['status']);?></p>
                            </div>
                        <?php else:?>
                            <?php if($user->settings->status == 0):?>
                            <div class='alert alert-danger'>
                                Ваша квалификация не подтверждена.
                                <?php echo CHtml::link('Подтвердить', Yii::app()->createUrl('userStatusRequest/create'), array('class'=>'btn btn-xs btn-default'));?>

                            </div>
                            <?php else:?>
                                <div class='flat-panel inside vert-margin20'>
                                    Ваш текущий статус:
                                        <strong>
                                        <?php echo $user->settings->getStatusName();?>
                                        </strong>
                                    <?php echo CHtml::link('Сменить статус', Yii::app()->createUrl('userStatusRequest/create'), array('class'=>'btn btn-xs btn-default'));?>
                                </div>
                            <?php endif;?>
                            
                        <?php endif;?>
                    
                        <?php if($user->settings->description):?>
                            <h3 class="left-align">О себе</h3>
                            <p><?php echo CHtml::encode($user->settings->description);?></p>
                            <hr />
                        <?php endif;?>
                      
                <div class='flat-panel inside vert-margin20'>             
                    <h3 class="left-align">Контакты <?php echo CHtml::link('<span class="glyphicon glyphicon-pencil"></span>', Yii::app()->createUrl('user/update', array('id' => Yii::app()->user->id)));?></h3>
                    
                    <p>
                        <strong>Город:</strong> <?php echo $user->town->name;?>
                    </p>
                    
                    <?php if($user->settings->phoneVisible):?>
                    <p>
                        <strong>Телефон:</strong> 
                        <?php echo $user->settings->phoneVisible;?>
                                
                    </p> 
                    <?php endif;?>
                    
                    <?php if($user->settings->emailVisible):?>
                    <p>
                        <strong>Email:</strong> 
                        <?php echo CHtml::encode($user->settings->emailVisible);?>
                           
                    </p>
                    <?php endif;?>
                    
                    <?php if($user->settings->site):?>
                    <p>
                        <strong>Сайт:</strong> <?php echo CHtml::encode($user->settings->site);?>
                    </p>
                    <?php endif;?>
                   
                    <?php if($user->registerDate):?>
                    <p>На сайте с <?php echo CustomFuncs::invertDate($user->registerDate);?></p>
                    <?php endif;?>
                    
                </div>
                
              
                <div class='flat-panel inside vert-margin20'>             
                    <h3 class="left-align">Образование</h3> 
                    <p>
                            <?php if($user->settings->education) echo $user->settings->education . ', ';?>
                            <?php if($user->settings->vuz) echo 'ВУЗ: ' . $user->settings->vuz . ', ';?>
                            <?php if($user->settings->vuzTownId) echo '(' . $user->settings->vuzTown->name . '), ';?>
                            <?php if($user->settings->educationYear) echo 'год окончания: ' . $user->settings->educationYear . '.';?>
                        
                    </p>
                </div> 
                
                           
                <?php if($user->categories):?>
                    <div class='flat-panel inside vert-margin20'>  
                    <h3 class="left-align">Специализации <?php echo CHtml::link('<span class="glyphicon glyphicon-pencil"></span>', Yii::app()->createUrl('user/update', array('id' => Yii::app()->user->id)));?></h3>
                    
                        <?php foreach ($user->categories as $cat): ?>
                        <span class="yurist-directions-item"><?php echo $cat->name; ?></span>
                        <?php endforeach;?>
                    </div>
                <?php endif;?>
                    
                
                            
                            
                    <?php if($user->settings->priceConsult  > 0 || $user->settings->priceDoc  > 0):?>
                        <div class='flat-panel inside vert-margin20'> 
                            <h3 class="left-align">Платные услуги <?php echo CHtml::link('<span class="glyphicon glyphicon-pencil"></span>', Yii::app()->createUrl('user/update', array('id' => Yii::app()->user->id)));?></h3>
                            <?php if($user->settings->priceConsult  > 0):?>
                                <p>Консультация от <?php echo $user->settings->priceConsult;?> руб.</p>
                            <?php endif;?>
                            <?php if($user->settings->priceDoc  > 0):?>
                                <p>Составление документа от <?php echo $user->settings->priceDoc;?> руб.</p>
                            <?php endif;?>
                        </div>        
                    <?php endif;?>
                            
                    <?php endif;?>        
                        
                    
                </div>
            </div>
            </div>

        </div>
        



        <?php if(Yii::app()->user->role == User::ROLE_CLIENT):?>
            <h2 class="header-block-light-grey">Мои вопросы</h2>
        <?php else:?>
            <h2 class="header-block-light-grey">Мои ответы</h2>
        <?php endif;?>
        
        <?php $this->widget('zii.widgets.CListView', array(
            'dataProvider'  =>  $questionsDataProvider,
            'itemView'      =>  'application.views.question._viewShort2',
            'viewData'      =>  array(
                'hideCategory'  =>  false,
            ),
            'emptyText'     =>  'Не найдено ни одного вопроса',
            'ajaxUpdate'    =>  false,
            'summaryText'   =>  '',
            'pager'         =>  array('class'=>'GTLinkPager') //we use own pager with russian words
    )); ?>
        