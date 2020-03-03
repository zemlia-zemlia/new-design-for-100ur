<?php
/* @var $this QuestionController */
/* @var $dataProvider CActiveDataProvider */

$this->setPageTitle('Вопросы без категории' . Yii::app()->name);
Yii::app()->clientScript->registerScriptFile('/js/question.js');
Yii::app()->clientScript->registerScriptFile('/js/admin/question.js');

$this->breadcrumbs = [
    'Вопросы без категории',
];
$this->widget('zii.widgets.CBreadcrumbs', [
    'homeLink' => CHtml::link('100 юристов', '/'),
    'separator' => ' / ',
    'links' => $this->breadcrumbs,
 ]);
?>
<div class="vert-margin30">
    <h1>Вопросы  без категории [<?php echo $questionsCount; ?>]</h1>
</div>

<table class="table table-bordered table-hover table-striped">
    <thead>
    <tr>
        <th>Вопрос</th>
		<th style="min-width: 700px;">Указать категорию вопроса</th>
    </tr>
    </thead>

    <?php foreach ($questions as $data):?>
    
    <?php
    switch ($data['status']) {
        case Question::STATUS_NEW:
            $statusClass = '';
            break;
        case Question::STATUS_MODERATED:
            $statusClass = 'info';
            break;
        case Question::STATUS_PUBLISHED:
            $statusClass = 'success';
            break;
        case Question::STATUS_SPAM:
            $statusClass = 'danger';
            break;
        default:
            $statusClass = '';
    }
?>

<tr class="<?php echo $statusClass; ?>" id="question-<?php echo $data['id']; ?>">
    <td>        
        <?php if ($data['title']):?>
            <h4 class='left-align'><?php echo CHtml::link(CHtml::encode($data['title']), Yii::app()->createUrl('/admin/question/view', ['id' => $data['id']])); ?></h4>
        <?php endif; ?>
        
        <p>
        <?php echo CHtml::encode($data['questionText']); ?>
        </p>
        
        <small>
            <?php if (Yii::app()->user->checkAccess(User::ROLE_EDITOR)):?>
                <?php if ($data['createDate']) {
    echo DateHelper::niceDate($data['createDate'], false, false);
}?>
                <?php
                    if ($data['publishDate']) {
                        echo "<span class='muted'>Опубликован " . DateHelper::niceDate($data['publishDate']) . '</span>';
                    }
                ?>
            &nbsp;
            <?php endif; ?>
             
            <b>ID:</b>
            <?php echo CHtml::link(CHtml::encode($data['id']), Yii::app()->createUrl('/admin/question/view', ['id' => $data['id']])); ?>
        </small>
        
        <?php if (Yii::app()->user->checkAccess(User::ROLE_ROOT) || Yii::app()->user->checkAccess(User::ROLE_EDITOR)):?>   
            <div class="vert-margin20">          
                <?php echo CHtml::link('Редактировать', Yii::app()->createUrl('/admin/question/update', ['id' => $data['id']]), ['class' => 'btn btn-primary btn-xs']); ?>
                <?php echo CHtml::ajaxLink('В спам', Yii::app()->createUrl('/admin/question/toSpam'), ['data' => 'id=' . $data['id'], 'type' => 'POST', 'success' => 'onSpamQuestion'], ['class' => 'btn btn-warning btn-xs']); ?>

                <?php if (Yii::app()->user->checkAccess(User::ROLE_ROOT)):?>
                    <?php echo CHtml::link('Удалить', Yii::app()->createUrl('/admin/question/delete', ['id' => $data['id']]), ['class' => 'btn btn-danger btn-xs']); ?>
                <?php endif; ?>
            </div> 
        <?php endif; ?>
    
    
    



    </td>
	
	<td>
	            <?php foreach ($allDirections as $directionId => $direction):?>
                <?php echo CHtml::link($direction['name'], '#', ['class' => 'set-category-link label label-primary', 'data-category' => $directionId, 'data-question' => $data['id']]); ?>
                
                    <?php if ($direction['children']):?>
                        <?php foreach ($direction['children'] as $childId => $child):?>
                             <?php echo CHtml::link($child['name'], '#', ['class' => 'set-category-link  label label-default', 'data-category' => $childId, 'data-question' => $data['id']]); ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <br />        
            <?php endforeach; ?>
	</td>
	
	
    <?php if (Yii::app()->user->checkAccess(User::ROLE_ROOT)):?>    
    
	
    <?php endif; ?>    
</tr>
    <?php endforeach; ?>
    
</table>