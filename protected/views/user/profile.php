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

<div class="panel panel-default">
    <div class="panel-body">
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
                    <?php echo CHtml::link('Редактировать профиль', Yii::app()->createUrl('user/update', array('id'=>Yii::app()->user->id)), array('class'=>'btn btn-xs btn-default'));?>
                    <?php endif;?>
                        
                </div>
                <div class="col-sm-9">
                    <?php if($user->registerDate):?>
                    <p><strong>На сайте</strong> с <?php echo CustomFuncs::invertDate($user->registerDate);?></p>
                    <?php endif;?>
                    
                    <?php if(Yii::app()->user->role == User::ROLE_JURIST):?>
                    <p><strong>Стаж</strong> с <?php echo $user->settings->startYear;?></p>
                    <?php endif;?>
                    
                    <p>
                        <strong>Город:</strong> 
                    <?php
                        echo Town::getName($user->townId);
                    ?>
                    </p>
                    <?php if(Yii::app()->user->role == User::ROLE_JURIST):?>
                    <p>
                        <strong>О себе:</strong><br />
                        <?php echo CHtml::encode($user->settings->description);?>
                    </p>
                    
                    <p>
                        <strong>Статус:</strong> 
                        <?php echo $user->settings->getStatusName();?>
                        <span class="label label-<?php echo ($user->settings->isVerified)?'success':'warning';?>">
                        <?php echo ($user->settings->isVerified)?"Подтвержден":"На проверке";?>
                        </span>
                    </p>
                    <?php endif;?>
                </div>
            </div>
            </div>

        </div>
        
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-body">
        <?php if(Yii::app()->user->role == User::ROLE_CLIENT):?>
            <h2>Мои вопросы</h2>
        <?php else:?>
            <h2>Мои ответы</h2>
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
        
    </div>
</div>
