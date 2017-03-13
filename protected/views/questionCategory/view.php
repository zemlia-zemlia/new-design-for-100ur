<?php
/* @var $this QuestionCategoryController */
/* @var $model QuestionCategory */

if($model->seoTitle) {
    $pageTitle = CHtml::encode($model->seoTitle);
} else {
    $pageTitle = CHtml::encode($model->name) . ". Консультация юриста и адвоката. ";
}

if(isset($_GET) && (int)$_GET['page']) {
    $pageNumber = (int)$_GET['page'];
    $pagesTotal = ceil($questionsDataProvider->totalItemCount / $questionsDataProvider->pagination->getPageSize());
    $pageTitle .= 'Страница ' . $pageNumber . ' из ' . $pagesTotal . '. ';
}
$this->setPageTitle($pageTitle . ' ' . Yii::app()->name);



if($model->seoDescription) {
    Yii::app()->clientScript->registerMetaTag(CHtml::encode($model->seoDescription), 'description');
} else {
    Yii::app()->clientScript->registerMetaTag("Получите бесплатную консультацию юриста. Ответы квалифицированных юристов на вопросы тематики " . CHtml::encode($model->name), 'description');
}

if($model->seoKeywords) {
    Yii::app()->clientScript->registerMetaTag(CHtml::encode($model->seoKeywords), 'keywords');
} 

Yii::app()->clientScript->registerLinkTag("canonical",NULL,Yii::app()->createUrl('/questionCategory/alias', array('name'=>$model->alias)));

$this->breadcrumbs = array();
if($parentCategory) {
    $this->breadcrumbs[$parentCategory['name']] = Yii::app()->createUrl('/questionCategory/alias',array('name'=>$parentCategory['alias']));
}   
//$this->breadcrumbs[] = $model->name;

?>

<?php 
//CustomFuncs::printr($model);
?>

<?php
    $this->widget('zii.widgets.CBreadcrumbs', array(
        'homeLink'=>CHtml::link('Вопрос юристу',"/"),
        'separator'=>' / ',
        'links'=>$this->breadcrumbs,
     ));
?>



<h1 class="header-block header-block-light-grey vert-margin30">
    <?php 
        if($model->seoH1) {
            echo CHtml::encode($model->seoH1);
        } else {
            echo CHtml::encode($model->name) . ', ' . 'консультации юриста и адвоката';
        }
    ?>
</h1>

<?php if(sizeof($childrenCategories)):?>
      
    <div class="row vert-margin30">
    <?php foreach($childrenCategories as $child):?>
        <div class="col-md-4">
            <small>
            <?php echo CHtml::link('<span class="glyphicon glyphicon-folder-open"></span>&nbsp;' . $child['name'], Yii::app()->createUrl('questionCategory/alias', array('name'=>CHtml::encode($child['alias']))));?>
            </small>
        </div>    
    <?php endforeach;?>
    </div>

<?php endif;?>

<?php if(sizeof($neighbours)):?>
      
    <div class="row vert-margin30">
    <?php foreach($neighbours as $neighbour):?>
        <div class="col-md-4">
            <small>
            <?php echo CHtml::link('<span class="glyphicon glyphicon-folder-open"></span> &nbsp;' . $neighbour['name'], Yii::app()->createUrl('questionCategory/alias', array('name'=>CHtml::encode($neighbour['alias']))));?>
            </small>
        </div>    
    <?php endforeach;?>
    </div>

<?php endif;?>

<?php if($model->description1):?>
        <div class="vert-margin30">
                        <?php echo $model->description1;?>
        </div>
<?php endif;?>


	<div class="form-container">		
            <div class="center-align">
            <?php
                // выводим виджет с номером 8800
                $this->widget('application.widgets.Hotline.HotlineWidget', array(
                ));
            ?>		
            </div>
            <div class="form-container-content">
                <h3 class="center-align header-block header-block-light-grey">Задать вопрос on-line<br/>доступно для ВСЕХ регионов РФ</h3>
                                
                <?php echo $this->renderPartial('application.views.question._formBrief', array(
                    'newQuestionModel'  =>  $newQuestionModel,
                ));?>
                              
            </div>
	</div>

			
			
        <div class="flat-panel vert-margin30">
        <h2 class="header-block header-block-green">Последние вопросы юристам</h2>
        <div class="header-block-green-arrow"></div>
        
        <div class="inside">
        <?php foreach($questions as $question):?>
        <div class="row">
            
            <div class="col-sm-9">
                <p><?php echo CHtml::link(CHtml::encode($question['title']), Yii::app()->createUrl('question/view', array('id'=>$question['id'])));?></p>
            </div>
            <div class="col-sm-3">
                
                <?php if($question['counter'] == 1) {
                    echo "<img src='/pics/2017/icon_checkmark.png' alt='Есть ответ' /> <span class='text-success'>Есть ответ</span>";
                } elseif($question['counter']>1) {
                    echo "<img src='/pics/2017/icon_checkmark.png' alt='Есть ответ' /> <span class='text-success'>" . $question['counter'] . ' ' . CustomFuncs::numForms($question['counter'], 'ответ', 'ответа', 'ответов') . "</span>";
                } else {
                    echo "Нет ответа";
                }
                ?>
                
            </div>
        </div>
        
        <?php endforeach;?>  
        </div>
        </div>

<?php if($model->description2):?>

            <?php echo $model->description2;?>

<?php endif;?>

