<?php
/* @var $this QuestionCategoryController */
/* @var $model QuestionCategory */

if($model->seoTitle) {
    $pageTitle = CHtml::encode($model->seoTitle);
} else {
    $pageTitle = CHtml::encode($model->name) . ". Консультация юриста и адвоката. ";
}

if(isset($_GET) && (int)$_GET['page'] && $questionsDataProvider->pagination) {
    $pageNumber = (int)$_GET['page'];
    $pagesTotal = ceil($questionsDataProvider->totalItemCount / $questionsDataProvider->pagination->getPageSize());
    $pageTitle .= 'Страница ' . $pageNumber . ' из ' . $pagesTotal . '. ';
}
$this->setPageTitle($pageTitle);



if($model->seoDescription) {
    Yii::app()->clientScript->registerMetaTag(CHtml::encode($model->seoDescription), 'description');
} else {
    Yii::app()->clientScript->registerMetaTag("Получите бесплатную консультацию юриста. Ответы квалифицированных юристов на вопросы тематики " . CHtml::encode($model->name), 'description');
}

if($model->seoKeywords) {
    Yii::app()->clientScript->registerMetaTag(CHtml::encode($model->seoKeywords), 'keywords');
} 

Yii::app()->clientScript->registerLinkTag("canonical",NULL,Yii::app()->createUrl('/questionCategory/alias', $model->getUrl()));

// нашел какой-то метатег чтобы он подгружал картинку когда вставляешь сссылку в группе
// <meta property="og:image" content="https://100yuristov.com/pics/2017/100_yuristov_logo.svg">

// временно отключаем запрет индексации недозаполненных категорий
//Yii::app()->clientScript->registerMetaTag(($model->isIndexingAllowed())?'all':'noindex', "robots");

$this->breadcrumbs = array('Темы вопросов' => array('/cat'));

foreach($ancestors as $ancestor) {
    $this->breadcrumbs[$ancestor->name] = Yii::app()->createUrl('questionCategory/alias', $ancestor->getUrl());
}
$this->breadcrumbs[] = $model->name;

?>

<?php
    $this->widget('zii.widgets.CBreadcrumbs', array(
        'homeLink'=>CHtml::link('Юридическая консультация',"/"),
        'separator'=>' / ',
        'links'=>$this->breadcrumbs,
     ));
?>

<?php 
if($model->seoH1) {
    $pageTitle = CHtml::encode($model->seoH1);
} else {
    $pageTitle = CHtml::encode($model->name) . ', ' . 'консультации юриста и адвоката';
}
?>

<div class="category-hero">
     <?php if($model->image):?>
     <img src="<?php echo QuestionCategory::IMAGES_DIRECTORY . $model->image;?>" alt="<?php echo $pageTitle;?>" title="<?php echo $pageTitle;?>" class="img-responsive hidden-xs" />
     <?php endif;?>
     
<h1>
    <?php 
        echo $pageTitle;
    ?>
    
    <?php
        if(Yii::app()->user->checkAccess(User::ROLE_EDITOR)) {
            echo CHtml::link("<span class='glyphicon glyphicon-pencil'><span>", Yii::app()->createUrl('/admin/questionCategory/update', array('id'=>$model->id)), array('target'=>'_blank'));
        }
    ?>
</h1>
</div>

<?php if($model->description1):?>
    <div class="vert-margin30">
        <?php echo $model->description1;?>
    </div>
<?php endif;?>

	<div class="flat-panel inside">		
            <div class="center-align">
            <?php
                // выводим виджет с номером 8800
                $this->widget('application.widgets.Hotline.HotlineWidget', array(
                    'showAlways'    =>  true,
                ));
            ?>		
            </div>
	</div>
<br/>

<div class="">

<?php if(sizeof($children)):?>
    
    <h2 class="vert-margin20">Смотрите также темы:</h2>

    <?php $itemsCount =  sizeof($children);?>

    <div class="row">
        <?php foreach($children as $index=>$child):?>

            <?php if($index == 0 || $index == floor($itemsCount/3) || $index == floor(2*$itemsCount/3)):?>
                <div class="col-md-4">
            <?php endif;?>
            <small>
                <p>
                <?php echo CHtml::link('<span class="glyphicon glyphicon-folder-open"></span>&nbsp; ' . $child->name, Yii::app()->createUrl('questionCategory/alias', $child->getUrl()));?>
                </p>
            </small>

            <?php if($index == $itemsCount-1 || $index == floor($itemsCount/3)-1 || $index == floor(2*$itemsCount/3)-1):?>
                </div>
            <?php endif;?>  
        <?php endforeach;?>
    </div>

<?php endif;?>
</div>


<div class="row vert-margin30 ">
<?php if(sizeof($neighboursPrev)):?>
      
    <div class="col-md-6">
    <?php foreach($neighboursPrev as $neighbour):?>
        
        <small>
            <p>
                <?php echo CHtml::link('<span class="glyphicon glyphicon-folder-open"></span> &nbsp;' . $neighbour->name, Yii::app()->createUrl('questionCategory/alias', $neighbour->getUrl()));?>
            </p>
        </small>
          
    <?php endforeach;?>
    </div>  
<?php endif;?>

<?php if(sizeof($neighboursNext)):?>
    <div class="col-md-6">  
    <?php foreach($neighboursNext as $neighbour):?>
        
        <small>
            <p>
                <?php echo CHtml::link('<span class="glyphicon glyphicon-folder-open"></span> &nbsp;' . $neighbour->name, Yii::app()->createUrl('questionCategory/alias', $neighbour->getUrl()));?>
            </p>
        </small>
          
    <?php endforeach;?>
    </div>  
<?php endif;?>
</div>



<?php if(Yii::app()->user->isGuest || Yii::app()->user->role == User::ROLE_CLIENT):?>        
    <div class="vert-margin30 blue-block inside">
        <div class="row">
            <div class="col-sm-8 center-align">
                <h3>Ваш вопрос требует составления документа?</h3>
                <p>Доверьте это опытным юристам</p>
            </div>
            <div class="col-sm-4 center-align">
                <p></p>
                <?php echo CHtml::link('Заказать документ', Yii::app()->createUrl('question/docs'), ['class' => 'yellow-button']);?>
            </div>
        </div>
    </div>
<?php endif;?>


<?php if($model->description2):?>

            <?php echo $model->description2;?>

<?php endif;?>

