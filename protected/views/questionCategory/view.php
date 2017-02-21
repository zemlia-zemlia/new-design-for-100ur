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
				<h2 class="header-block header-block-green"> <strong class="glyphicon glyphicon-earphone"></strong> Горячая линия юридических консультаций</h2>
				<br/>
				<p style="text-align: center;">
					<span style="font-size: 25pt; color: #39b778;"><strong>8-800-500-61-85</strong></span><br/>
				</p>
				<p class="text-muted" style="text-align: center">
					<small>
					Москва
					Санкт-Петербург 
					Екатеринбург
					Нижний Новгород
					Волгоград
					Красноярск<br/>
					<b>Звонки принимаются с 10:00 до 19:00 (МСК), <a href="/question/create/">письменные обращения</a> КРУГЛОСУТОЧНО</b>
					</small>
				</p>
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

     



<!-- Форма --> 

            <div class="form-container form-container-content flat-panel">
                <h3 class="header-block header-block-green">Задать свой вопрос</h3>
                <div class="header-block-green-arrow"></div>
                                
                <div class="inside">
                <?php $form=$this->beginWidget('CActiveForm', array(
                        'id'                    =>  'question-form',
                        'enableAjaxValidation'  =>  false,
                        'action'                =>  Yii::app()->createUrl('question/create'),
                )); ?>
                
                <div class="row">
                    <div class="col-md-7">
                        <div class="form-group">
                                <?php echo $form->labelEx($newQuestionModel,'questionText'); ?>
                                <?php echo $form->textArea($newQuestionModel,'questionText', array('class'=>'form-control', 'rows'=>6, 'placeholder'=>'Добрый день!...')); ?>
                                <?php echo $form->error($newQuestionModel,'questionText'); ?>
                        </div>
                    </div>

                    <div class="col-md-5">
                        <div class="form-group">
                            <label>Ваше имя *</label>
                            <?php echo $form->textField($newQuestionModel,'authorName', array('class'=>'form-control', 'placeholder'=>'Иванов Иван')); ?>
                            <?php echo $form->error($newQuestionModel,'authorName'); ?>
                        </div>
						<div class="form-group" id="form-submit-wrapper">
                                <?php echo CHtml::submitButton($newQuestionModel->isNewRecord ? 'Задать вопрос юристу' : 'Сохранить', array('class'=>'button button-blue-gradient btn-block', 'onclick'=>'yaCounter26550786.reachGoal("simple_form_submit"); return true;')); ?>
                        </div>
					</div>
                </div> 
                <?php $this->endWidget(); ?>
                </div>         
            </div>

<!-- Конец формы --> 