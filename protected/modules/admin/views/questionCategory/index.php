<?php
/* @var $this QuestionCategoryController */
/* @var $dataProvider CActiveDataProvider */

$this->setPageTitle("Категории вопросов. ". Yii::app()->name);


$this->breadcrumbs=array(
        'Вопросы и ответы'=>array('/admin/question'),
	'Категории вопросов',
);

$this->widget('zii.widgets.CBreadcrumbs', array(
    'homeLink'=>CHtml::link('100 юристов',"/"),
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
<h1>Категории вопросов 
    <?php echo CHtml::link('Добавить категорию', Yii::app()->createUrl('/admin/questionCategory/create'), array('class'=>'btn btn-primary')); ?>
</h1>
</div>

<?php if($totalCategoriesCount>0):?>
    <?php 
    $partWithDescription =  ($totalCategoriesCount - $emptyCategoriesCount) / $totalCategoriesCount;
    ?>

    <div class="progress">
      <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $partWithDescription*100;?>%;">
        С описанием: <?php echo ($totalCategoriesCount - $emptyCategoriesCount); ?> из <?php echo $totalCategoriesCount;?>
      </div>
    </div>
<?php endif;?>

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
		<th>Управление</th>
    </tr>
<?php foreach($categoriesArray as $rootId=>$rootCategory):?>
    
    <?php 
           $padding = $rootCategory['level']*15; 
    ?>
    
    <tr>
        <td style="padding-left:<?php echo $padding;?>px;">
            
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

<?php endforeach;?>
</table>