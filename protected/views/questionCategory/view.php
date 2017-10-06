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
$this->setPageTitle($pageTitle . ' ' . Yii::app()->name);



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



<h1 class="header-block header-block-light-grey vert-margin30">
    <?php 
        if($model->seoH1) {
            echo CHtml::encode($model->seoH1);
        } else {
            echo CHtml::encode($model->name) . ', ' . 'консультации юриста и адвоката';
        }
    ?>
    
    <?php
        if(Yii::app()->user->checkAccess(User::ROLE_EDITOR)) {
            echo CHtml::link("<span class='glyphicon glyphicon-pencil'><span>", Yii::app()->createUrl('/admin/questionCategory/update', array('id'=>$model->id)), array('target'=>'_blank'));
        }
    ?>
</h1>

<?php if($model->description1):?>
    <div class="vert-margin30">
        <?php echo $model->description1;?>
    </div>
<?php endif;?>

<div class="">

<?php if(sizeof($children)):?>
    <?php $itemsCount =  sizeof($children);?>

    <div class="row vert-margin30">
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





			
			
        <div class="flat-panel vert-margin30">
        <h2 class="header-block header-block-green">Последние вопросы юристам</h2>
        <div class="header-block-green-arrow"></div>
        
        <div class="inside">
        <?php foreach($questions as $question):?>
            <div class="row vert-margin20">

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

