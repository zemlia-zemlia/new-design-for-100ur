<?php
/* @var $this UserController */
/* @var $dataProvider CActiveDataProvider */

$this->pageTitle = "Сотрудники. " . Yii::app()->name;


$this->breadcrumbs=array(
	'Сотрудники',
);


?>
<div  class="vert-margin30">
    <h1>Сотрудники.
</div>

<div class="right-align vert-margin30">
    <?php echo CHtml::link('Добавить сотрудника', Yii::app()->createUrl('/admin/user/create'), array('class'=>'btn btn-success'));?>
</div>


<!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#active-users" aria-controls="active-users" role="tab" data-toggle="tab">Активные</a></li>
    <li role="presentation"><a href="#inactive-users" aria-controls="inactive-users" role="tab" data-toggle="tab">Неактивные</a></li>
  </ul>

  <!-- Tab panes -->
  <div class="tab-content">
      <div role="tabpanel" class="tab-pane active" id="active-users">
          <table class="table table-bordered table-hover table-striped">
            <thead>
            <tr>
                <th>Имя</th>
                <th>Email</th>
                <th>Редактировать</th>
            </tr>
            </thead>
            <?php $currentRole = '';?>
            <?php foreach($usersArray as $data):?>
                <?php if($data->active == 1):?>
                    <?php 
                        if($currentRole != $data->role) {
                            echo "<tr><th colspan='4'>" . $data->getRoleName() . "</th></tr>";
                            $currentRole = $data->role;
                        }
                    ?>
                    <tr>
                        <td>
                            <?php echo CHtml::link(CHtml::encode($data->name . ' ' . $data->name2 . ' ' . $data->lastName), array('view', 'id'=>$data->id)); ?>
                            <?php if($data->active==0):?>
                            <span class="label label-default">неактивен</span>
                            <?php endif;?>
                            <div class="muted">
                                <?php echo CHtml::encode($data->position); ?>
                            </div>
                            <?php echo $data->getRoleName(); ?>
                        </td>
                        <td>
                            <?php echo CHtml::encode($data->email); ?><br />
                            <?php echo CHtml::encode($data->phone); ?>
                        </td>
                        <td>
                            <?php echo CHtml::link('Редактировать', Yii::app()->createUrl('/admin/user/update',array('id'=>$data->id)), array('class'=>'btn btn-primary'));?>
                        </td>
                    </tr>
                <?php endif;?>
            <?php endforeach;?>
        </table>
          
      </div>
      <div role="tabpanel" class="tab-pane" id="inactive-users">
          <table class="table table-bordered table-hover table-striped">
            <thead>
            <tr>
                <th>Имя</th>
                <th>Email</th>
                <th>Редактировать</th>
            </tr>
            </thead>
            <?php $currentRole = '';?>
            <?php foreach($usersArray as $data):?>
                <?php if($data->active == 0):?>
                    <?php 
                        if($currentRole != $data->role) {
                            echo "<tr><th colspan='4'>" . $data->getRoleName() . "</th></tr>";
                            $currentRole = $data->role;
                        }
                    ?>
                    <tr>
                        <td>
                            <?php echo CHtml::link(CHtml::encode($data->name . ' ' . $data->name2 . ' ' . $data->lastName), array('view', 'id'=>$data->id)); ?>
                            <?php if($data->active==0):?>
                            <span class="label label-default">неактивен</span>
                            <?php endif;?>
                            <div class="muted">
                                <?php echo CHtml::encode($data->position); ?>
                            </div>
                            <?php echo $data->getRoleName(); ?>
                        </td>
                        <td>
                            <?php echo CHtml::encode($data->email); ?><br />
                            <?php echo CHtml::encode($data->phone); ?>
                        </td>
                        <td>
                            <?php echo CHtml::link('Редактировать', Yii::app()->createUrl('/admin/user/update',array('id'=>$data->id)), array('class'=>'btn btn-primary'));?>
                        </td>
                    </tr>
                <?php endif;?>
            <?php endforeach;?>
        </table>
          
      </div>
  </div>
  
