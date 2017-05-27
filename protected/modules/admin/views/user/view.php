<?php
/* @var $this UserController */
/* @var $model User */
$this->pageTitle = 'Профиль пользователя ' . CHtml::encode($model->name) . '. ' . Yii::app()->name;

$this->breadcrumbs=array(
	'Пользователи'              =>  array('index'),
	CHtml::encode($model->name . ' ' . $model->lastName),
);

$this->widget('zii.widgets.CBreadcrumbs', array(
    'homeLink'=>CHtml::link('100 Юристов',"/admin/"),
    'separator'=>' / ',
    'links'=>$this->breadcrumbs,
 ));
?>

<h1 class="vert-margin30"><?php echo CHtml::encode($model->name) . " " . CHtml::encode($model->name2) . " " . CHtml::encode($model->lastName);?></h1>

<div class="row vert-margin30">
<?php if($model->avatar): ?>
<div class="col-md-2">
    <?php echo CHtml::image($model->getAvatarUrl('thumb'),'', array('class'=>'img-responsive'));?>
    
    <?php if($model->id == Yii::app()->user->id || Yii::app()->user->checkAccess(User::ROLE_ROOT)):?>
        <?php echo CHtml::link('Удалить аватар', Yii::app()->createUrl('/admin/user/removeAvatar',array('id'=>$model->id)));?>
    <?php endif;?>
</div>
<?php endif;?>

<div class="col-md-10">
<table class="table table-bordered">
    
<?php 
// Показываем контактные данные сотрудников только секретарю и менеджерам
if(Yii::app()->user->checkAccess(User::ROLE_MANAGER) || Yii::app()->user->role == User::ROLE_SECRETARY):?>    
     <tr>
        <td><strong><?php echo $model->getAttributeLabel('email');?></strong></td>
        <td><?php echo CHtml::encode($model->email); ?></td>
    </tr>
    <tr>
        <td><strong><?php echo $model->getAttributeLabel('phone');?></strong></td>
        <td><?php echo CHtml::encode($model->phone); ?></td>
    </tr>
<?php endif;?>    
       
    <tr>
        <td><strong><?php echo $model->getAttributeLabel('birthday');?></strong></td>
        <td><?php echo CustomFuncs::invertDate($model->birthday); ?></td>
    </tr>

    
    <?php if($model->settings):?>
    <tr>
        <td><strong>Псевдоним</strong></td>
        <td><?php echo CHtml::encode($model->settings->alias); ?></td>
    </tr>
    <tr>
        <td><strong>Город</strong></td>
        <td><?php echo CHtml::encode($model->town->name . ' (' . $model->town->region->name . ')'); ?></td>
    </tr>
    <tr>
        <td><strong>Год начала работы</strong></td>
        <td><?php echo CHtml::encode($model->settings->startYear); ?></td>
    </tr>
    <tr>
        <td><strong>Описание</strong></td>
        <td><?php echo CHtml::encode($model->settings->description); ?></td>
    </tr>
    <tr>
        <td><strong>Статус</strong></td>
        <td>
            <?php echo $model->settings->getStatusName(); ?>
            <?php if($model->settings->isVerified):?>
                <span class="label label-success">подтвержден</span>
            <?php else:?>
                <span class="label label-warning">не подтвержден</span>
            <?php endif;?>
        </td>
    </tr>
    
        <?php if($model->categories):?>
            <tr>
                <td><strong>Специализации</strong></td>
                <td>
                    <?php foreach ($model->categories as $cat): ?>
                    <span class="label label-default"><?php echo $cat->name; ?></span>
                    <?php endforeach;?>
                </td>
            </tr>

        <?php endif;?>
    
    <?php endif;?>
            
    <tr>
        <td>Баланс</td>
        <td><?php echo $model->balance;?> руб.</td>
    </tr>
</table>
    
    <?php echo CHtml::link('Редактировать профиль', Yii::app()->createUrl('/admin/user/update',array('id'=>$model->id)), array('class'=>'btn btn-primary'));?>

    <?php if($transactionsDataProvider->totalItemCount):?>
<h2>Транзакции</h2>

<table class="table table-bordered">
    <tr>
        <th>Время</th>
        <th>Кампания</th>
        <th>Сумма</th>
        <th>Описание</th>
    </tr>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'  =>  $transactionsDataProvider,
	'itemView'      =>  'application.views.transactionCampaign._view',
        'emptyText'     =>  'Не найдено ни одной транзакции',
        'summaryText'   =>  'Показаны транзакции с {start} до {end}, всего {count}',
        'pager'         =>  array('class'=>'GTLinkPager') //we use own pager with russian words
)); ?>
</table>

<?php endif;?>

</div>
</div>
    
    
