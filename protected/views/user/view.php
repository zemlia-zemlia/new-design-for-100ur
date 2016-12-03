<?php
/* @var $this UserController */
/* @var $model User */

$this->breadcrumbs=array(
	CHtml::encode($model->settings->alias),
);

$this->setPageTitle("Юрист ". CHtml::encode($model->settings->alias) . '. ' . Yii::app()->name);
        
$this->widget('zii.widgets.CBreadcrumbs', array(
    'homeLink'=>CHtml::link('Юристы и Адвокаты',"/"),
    'separator'=>' / ',
    'links'=>$this->breadcrumbs,
 ));
            
?>


<div class="panel panel-default gray-panel">
    <div class="panel-body">
           
        <h1 class="vert-margin30">
            <?php echo CHtml::encode($model->settings->alias);?>
        </h1>
        
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-3 center-align">
                    <p>
                        <img src="<?php echo $model->getAvatarUrl();?>" class="gray-panel" />
                    </p>    
                    <?php echo CHtml::link('Задать вопрос', Yii::app()->createUrl('question/create'), array('class'=>'btn btn-info'));?>

                </div>
                <div class="col-sm-9">
                    <p>
                        <strong>Город:</strong> <?php echo $model->settings->town->name;?>
                    </p>
                    
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
            
                    <?php if($model->settings->startYear):?>
                    <p>
                        <strong>Год начала работы:</strong> <?php echo $model->settings->startYear;?>
                    </p>
                    <?php endif;?>
                    
                    <?php if($model->settings && $model->settings->status):?>
                    <p>
                        <strong>Статус:</strong> 
                        <?php echo $model->settings->getStatusName();?>
                        
                        <?php if($model->settings->isVerified == 1):?>
                            <span class="label label-success">подтверждён</span>
                        <?php endif;?>
                        
                    </p>
                    <?php endif;?>
                    <p>
                        <strong>Образование:</strong> 
                            
                            <?php if($model->settings->education) echo $model->settings->education . ', ';?>
                            <?php if($model->settings->vuz) echo 'ВУЗ: ' . $model->settings->vuz . ', ';?>
                            <?php if($model->settings->vuzTownId) echo '(' . $model->settings->vuzTown->name . '), ';?>
                            <?php if($model->settings->educationYear) echo 'год окончания: ' . $model->settings->educationYear . '.';?>
                        
                    </p>
                    
                    <!--
                    <?php if($model->phone):?>
                    <p>
                        <strong>Телефон:</strong> <?php echo $model->phone;?>
                    </p>
                    <?php endif;?>
                    -->
                </div>
            </div>
        </div>
        
        
    </div>
</div>


<div class="panel panel-default gray-panel">
    <div class="panel-body">
        
        <h2>Последние ответы</h2>
        
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
</div>