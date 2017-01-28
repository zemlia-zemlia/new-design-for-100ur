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
            <h2>
                <?php
                    echo CHtml::encode($user->name . ' ' . $user->name2 . ' ' . $user->lastName);
                ?>
            </h2>
            
            <div class="container-fluid">
            <div class="row">
                <div class="col-sm-3 center-align">
                    <p>
                        <img src="<?php echo $user->getAvatarUrl();?>" />
                    </p>  
                    <?php if($user->id === Yii::app()->user->id):?>    
                    <?php echo CHtml::link('Редактировать', Yii::app()->createUrl('user/update', array('id'=>Yii::app()->user->id)), array('class'=>'btn btn-xs btn-default'));?>
                    <?php endif;?>
                        
                </div>
                <div class="col-sm-9">
                    
                    <?php if(Yii::app()->user->role == User::ROLE_JURIST):?>
                    
                        <?php if($user->settings->hello):?>
                        <div class="alert alert-info">
                            <?php echo CHtml::encode($user->settings->hello);?>
                        </div>
                        <?php endif;?>
                    
                        Текущий статус:
                        <strong>
                        <?php echo $user->settings->getStatusName();?>
                        </strong>
                        
                        <?php echo CHtml::link('Сменить статус', Yii::app()->createUrl('userStatusRequest/create'), array('class'=>'btn btn-xs btn-default'));?>
                        
                        <?php if($lastRequest && $lastRequest['isVerified'] == 0):?>
                        <p>Активна заявка на подтверждение статуса <?php echo YuristSettings::getStatusNameByCode($lastRequest['status']);?></p>
                        <?php endif;?>
                    
                        <?php if($user->settings->description):?>
                            <h3 class="left-align">О себе</h3>
                            <p><?php echo CHtml::encode($user->settings->description);?></p>
                            <hr />
                        <?php endif;?>
                        
                    <h3 class="left-align">Контакты</h3>
                    
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
                   
                    <hr /> 
                    
                    <h3 class="left-align">Карьера</h3>    
                    <?php if($user->settings->startYear):?>
                    <p>
                        <strong>Год начала работы:</strong> <?php echo $user->settings->startYear;?>
                    </p>
                    <?php endif;?>
                    
                    <?php if($user->settings && $user->settings->status):?>
                    <p>
                        <strong>Статус:</strong> 
                        <?php echo $user->settings->getStatusName();?>
                        
                        <?php if($user->settings->isVerified == 1):?>
                            <span class="label label-success">подтверждён</span>
                        <?php endif;?>
                        
                    </p>
                    <?php endif;?>
                    <hr /> 
                    
                    <h3 class="left-align">Образование</h3> 
                    <p>
                            <?php if($user->settings->education) echo $user->settings->education . ', ';?>
                            <?php if($user->settings->vuz) echo 'ВУЗ: ' . $user->settings->vuz . ', ';?>
                            <?php if($user->settings->vuzTownId) echo '(' . $user->settings->vuzTown->name . '), ';?>
                            <?php if($user->settings->educationYear) echo 'год окончания: ' . $user->settings->educationYear . '.';?>
                        
                    </p>
                    <hr /> 
                    
                    <?php if($user->categories):?>
                    <h3 class="left-align">Специализации</h3>
                    
                        <?php foreach ($user->categories as $cat): ?>
                        <span class="yurist-directions-item"><?php echo $cat->name; ?></span>
                        <?php endforeach;?>
                    <hr />        
                    <?php endif;?>
                    
                    
                    <?php if($user->settings->priceConsult  > 0 || $user->settings->priceDoc  > 0):?>
                        <h3 class="left-align">Платные услуги</h3>
                        <?php if($user->settings->priceConsult  > 0):?>
                            <p>Консультация от <?php echo $user->settings->priceConsult;?> руб.</p>
                        <?php endif;?>
                        <?php if($user->settings->priceDoc  > 0):?>
                            <p>Составление документа от <?php echo $user->settings->priceDoc;?> руб.</p>
                        <?php endif;?>
                    <?php endif;?>
                            
                    <?php endif;?>        
                        
                    <?php if($user->registerDate):?>
                    <p><strong>На сайте</strong> с <?php echo CustomFuncs::invertDate($user->registerDate);?></p>
                    <?php endif;?>
                    
                    
                    
                    <p>
                        <strong>Город:</strong> 
                    <?php
                        echo Town::getName($user->townId);
                    ?>
                    </p>
                    
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
        