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
$this->setPageTitle($pageTitle . Yii::app()->name);



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

<h1><?php echo $pageTitle;?></h1>

<?php if($model->children):?>
    <div class="panel">
        <div class='panel-body'>
            <div class="row">
            <?php foreach($model->children as $child):?>
                <div class="col-md-4">
                    <?php echo CHtml::link($child->name, Yii::app()->createUrl('questionCategory/alias', array('name'=>CHtml::encode($child->alias))));?>
                </div>    
            <?php endforeach;?>
            </div>
        </div>
    </div>
<?php endif;?>

<?php if($model->description1):?>
    <div class="panel">
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
    <div class="panel">
        <div class='panel-body'>
            <?php echo $model->description2;?>
        </div>
    </div>
<?php endif;?>

<div class="vert-margin30">
<h3>На ваши вопросы отвечают:</h3>
<div class="panel">
    <div class='panel-body'>
        
    
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
</div>