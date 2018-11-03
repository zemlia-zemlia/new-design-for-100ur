<?php
/* @var $this UserController */
/* @var $dataProvider CActiveDataProvider */

$this->pageTitle = "Пользователи. " . Yii::app()->name;


$this->breadcrumbs=array(
	'Пользователи',
);


?>

<style>
    .table>thead>tr>th, .table>tbody>tr>th, .table>tfoot>tr>th, .table>thead>tr>td, .table>tbody>tr>td, .table>tfoot>tr>td {
        padding:1px;
    }
</style>

<div  class="vert-margin30">
    <h1>Пользователи: <?php echo $roleName;?> <?php if(Yii::app()->user->checkAccess(User::ROLE_ROOT)):?>
    <?php echo CHtml::link('Добавить', Yii::app()->createUrl('/admin/user/create'), array('class'=>'btn btn-success'));?>
 <?php endif;?>
</h1>
</div>


<div class="vert-margin30">
    <?php echo CHtml::link('Пользователи (клиенты)', Yii::app()->createUrl('admin/user/index',array('role'=>User::ROLE_CLIENT)));?> &nbsp;&nbsp;
    <?php echo CHtml::link('Юристы', Yii::app()->createUrl('admin/user/index',array('role'=>User::ROLE_JURIST)));?> &nbsp;&nbsp;
    <?php echo CHtml::link('Секретари', Yii::app()->createUrl('admin/user/index',array('role'=>User::ROLE_SECRETARY)));?> &nbsp;&nbsp;
    <?php echo CHtml::link('Покупатели', Yii::app()->createUrl('admin/user/index',array('role'=>User::ROLE_BUYER)));?> &nbsp;&nbsp;
    <?php echo CHtml::link('Контент-менеджеры', Yii::app()->createUrl('admin/user/index',array('role'=>User::ROLE_EDITOR)));?> &nbsp;&nbsp;
    <?php echo CHtml::link('Вебмастера', Yii::app()->createUrl('admin/user/index',array('role'=>User::ROLE_PARTNER)));?> &nbsp;&nbsp;
</div>

   <table class="table table-bordered table-hover table-striped">
      <thead>
      <tr>
          <th>ID</th>
          <th>Имя</th>
          <?php if ($role == User::ROLE_JURIST): ?>
            <th>Посл. акт.</th>
          <?php endif;?>
          <th>Город</th>
          <th>Email</th>
	  <th>Телефон</th>
          <?php if($role == User::ROLE_BUYER):?>
            <th>
                Камп.
            </th>
          <?php endif;?>
          <th>Редактировать</th>
      </tr>
      </thead>

      <?php $this->widget('zii.widgets.CListView', array(
              'dataProvider'  =>  $usersDataProvider,
              'itemView'      =>  '_viewTable',
              'emptyText'     =>  'Не найдено ни одного пользователя',
              'summaryText'   =>  'Показаны пользователи с {start} до {end}, всего {count}',
              'pager'         =>  array('class'=>'GTLinkPager') //we use own pager with russian words

      )); ?>
     </table>

