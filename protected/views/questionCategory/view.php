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

Yii::app()->clientScript->registerLinkTag("canonical",NULL,"http://".$_SERVER['SERVER_NAME'].Yii::app()->createUrl('/questionCategory/alias', array('name'=>$model->alias)));

$this->breadcrumbs = array();
if($model->parent instanceof QuestionCategory) {
    $this->breadcrumbs[$model->parent->name] = Yii::app()->createUrl('/questionCategory/alias',array('name'=>$model->parent->alias));
}   
$this->breadcrumbs[] = $model->name;

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

<?php if($model->children):?>
    <div class="panel gray-panel">
        <div class='panel-body'>
            
            <div class="row">
            <?php foreach($model->children as $child):?>
                <div class="col-md-4">
					<small>
                    <?php echo CHtml::link('<span class="glyphicon glyphicon-folder-open"></span>&nbsp;' . $child->name, Yii::app()->createUrl('questionCategory/alias', array('name'=>CHtml::encode($child->alias))));?>
					</small>
                </div>    
            <?php endforeach;?>
            </div>
        </div>
    </div>
<?php endif;?>

<div class="panel gray-panel">
    <div class='panel-body'>
		<h1>
            <?php 
                if($model->seoH1) {
                    echo CHtml::encode($model->seoH1);
                } else {
                    echo CHtml::encode($model->name) . ', ' . 'консультации юриста и адвоката';
                }
            ?>
        </h1>
    </div>
</div>

<?php if($model->description1):?>
    <div class="panel gray-panel">
        <div class='panel-body'>
            <?php echo $model->description1;?>
        </div>
    </div>
<?php endif;?>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'  =>  $questionsDataProvider,
	'itemView'      =>  'application.views.question._view',
        'viewData'      =>  array(
            'hideCategory'  =>  false,
        ),
        'emptyText'     =>  'Не найдено ни одного вопроса',
        'ajaxUpdate'    =>  false,
        'summaryText'   =>  '',
        'pager'         =>  array('class'=>'GTLinkPager') //we use own pager with russian words
)); ?>

<?php if($model->description2):?>
    <div class="panel gray-panel">
        <div class='panel-body'>
            <?php echo $model->description2;?>
        </div>
    </div>
<?php endif;?>

<div class="panel gray-panel">
    <div class='panel-body'>
        <h3>На ваши вопросы отвечают:</h3>
    
        <div class="row">

            <div class="col-md-4 col-sm-4 center-align">  
                <img src="/pics/yurist2.jpg" alt="При поддержке Министерства Юстиции" class="img-responsive center-block" /> 
                <p class="center-align"><b>Штуцер Максим Федорович</b><br />
                            <small> Семейное право<br/>
                            Уголовное право<br/>
                            Корпоративное право<br/>
                            Договорные отношения<br/>
                            Банковская деятельность<br/>
                            Кредиты</small>
                </p>
                    <a href="/question/create/?utm_source=100yuristov&utm_campaign=yuristi&utm_medium=button&utm_content=shtutzer" class="btn btn-warning btn-xs" rel="nofollow">Получить консультацию</a>

            </div>
            
            <div class="col-md-4 col-sm-4 center-align"> 
                <img src="/pics/yurist3.jpg" alt="При поддержке Министерства Юстиции" class="img-responsive center-block" /> 
                <p class="center-align"><b>Самойлов Николай Николаевич</b><br />
                            <small> Семейное право<br/>
                            Уголовное право<br/>
                            Трудовое право<br/>
                            Договорные отношения<br/>
                            Налогое право <br/>
                            </small>
                    </p>
                    <a href="/question/create/?utm_source=100yuristov&utm_campaign=yuristi&utm_medium=button&utm_content=samoilov" class="btn btn-warning btn-xs" rel="nofollow">Получить консультацию</a>

            </div>

            

            <div class="col-md-4 col-sm-4 center-align">  
                <img src="/pics/yurist4.jpg" alt="При поддержке Министерства Юстиции" class="img-responsive center-block" /> 
                <p class="center-align"><b>Тихонова Анастасия Викторовна</b><br />
                            <small> Семейное право<br/>
                            Уголовное право<br/>
                            Корпоративное право<br/>
                            Договорные отношения<br/>
                            Банковская деятельность<br/></small>
                </p>
                <a href="/question/create/?utm_source=100yuristov&utm_campaign=yuristi&utm_medium=button&utm_content=tikhonova" class="btn btn-warning btn-xs" rel="nofollow">Получить консультацию</a>

            </div>

        </div>
    </div>
</div>        



<!-- Форма --> 
<noindex>
            <div class="form-container form-container-content">
                <h3 class="center-align">Задать свой вопрос</h3>
                                
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
                                <?php echo CHtml::submitButton($newQuestionModel->isNewRecord ? 'Задать вопрос юристу' : 'Сохранить', array('class'=>'btn btn-warning btn-block', 'onclick'=>'yaCounter26550786.reachGoal("simple_form_submit"); return true;')); ?>
                        </div>
					</div>
                </div> 
                <?php $this->endWidget(); ?>
                              
            </div>
</noindex>
<!-- Конец формы --> 