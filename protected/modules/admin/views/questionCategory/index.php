<?php
/* @var $this QuestionCategoryController */
/* @var $dataProvider CActiveDataProvider */

$this->setPageTitle("Категории вопросов. ". Yii::app()->name);


$this->breadcrumbs=array(
        'Вопросы и ответы'=>array('/admin/question'),
	'Категории вопросов',
);

$this->widget('zii.widgets.CBreadcrumbs', array(
    'homeLink'=>CHtml::link('CRM',"/"),
    'separator'=>' / ',
    'links'=>$this->breadcrumbs,
 ));

 
 
?>

            <style>
    .comments-item, .table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
        padding:1px 1px;
    }
</style>

<div class="vert-margin30">
<h1>Категории вопросов</h1>
</div>

<div class="right-align vert-margin30">
<?php echo CHtml::link('Добавить категорию', Yii::app()->createUrl('/admin/questionCategory/create'), array('class'=>'btn btn-primary')); ?>
</div>

<table class="table table-bordered table-hover" >
    <tr>
        <th>Название категории</th>
        <th>Текст описания (верх)</th>
        <th>Текст описания (низ)</th>
        <th>H1</th>
        <th>Title</th>
        <th>Descr.</th>
        <th>Keyw.</th>
        <th>Напр</th>
    </tr>
<?php foreach($categoriesArray as $rootId=>$rootCategory):?>
    <tr>
        <td>
            <?php echo CHtml::link(CHtml::encode($rootCategory['name']), array('view', 'id'=>$rootId)); ?></strong>
        (id <?php echo $rootId;?>) 
        <?php echo CHtml::link("+подкатегория", array('create', 'parentId'=>$rootId), array('class'=>'btn btn-xs btn-primary')); ?>
        </td>
        <td><?php echo QuestionCategory::checkIfArrayPropertyFilled($rootCategory, 'description1');?></td>
        <td>
            <?php echo QuestionCategory::checkIfArrayPropertyFilled($rootCategory, 'description2');?>
        </td>
        <td>
            <?php echo QuestionCategory::checkIfArrayPropertyFilled($rootCategory, 'seoH1');?>
        </td>
        <td>
            <?php echo QuestionCategory::checkIfArrayPropertyFilled($rootCategory, 'seoTitle');?>
        </td>
        <td>
            <?php echo QuestionCategory::checkIfArrayPropertyFilled($rootCategory, 'seoDescription');?>
        </td>
        <td>
            <?php echo QuestionCategory::checkIfArrayPropertyFilled($rootCategory, 'seoKeywords');?>
        </td>
        <td>
            <?php echo QuestionCategory::checkIfArrayPropertyFilled($rootCategory, 'isDirection');?>
        </td>
        <td>
            <?php echo CHtml::link("Ред.", array('update', 'id'=>$rootId)); ?>
        </td>
    </tr>
    
    <?php if(!isset($rootCategory['children'])) continue;?>
    
    <?php foreach($rootCategory['children'] as $childId=>$child):?>
    
    <tr>
        <td>
           &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?php echo CHtml::link(CHtml::encode($child['name']), array('view', 'id'=>$childId)); ?></strong>
        (id <?php echo $childId;?>) 
        </td>
        <td><?php echo QuestionCategory::checkIfArrayPropertyFilled($child, 'description1');?></td>
        <td>
            <?php echo QuestionCategory::checkIfArrayPropertyFilled($child, 'description2');?>
        </td>
        <td>
            <?php echo QuestionCategory::checkIfArrayPropertyFilled($child, 'seoH1');?>
        </td>
        <td>
            <?php echo QuestionCategory::checkIfArrayPropertyFilled($child, 'seoTitle');?>
        </td>
        <td>
            <?php echo QuestionCategory::checkIfArrayPropertyFilled($child, 'seoDescription');?>
        </td>
        <td>
            <?php echo QuestionCategory::checkIfArrayPropertyFilled($child, 'seoKeywords');?>
        </td>
        <td>
            <?php echo QuestionCategory::checkIfArrayPropertyFilled($child, 'isDirection');?>
        </td>
        <td>
            <?php echo CHtml::link("Ред.", array('update', 'id'=>$childId)); ?>
        </td>
    </tr>
    
    <?php endforeach;?>
<?php endforeach;?>
</table>