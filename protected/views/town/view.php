<?php
/* @var $this TownController */
/* @var $model Town */

$pageTitle = $model->createPageTitle();
        
if(isset($_GET) && (int)$_GET['Question_page'] && $dataProvider->pagination) {
    $pageNumber = (int)$_GET['Question_page'];
    $pagesTotal = ceil($dataProvider->totalItemCount / $dataProvider->pagination->getPageSize());
    $pageTitle .= '. Страница ' . $pageNumber . ' из ' . $pagesTotal . '. ';
}
$this->setPageTitle($pageTitle);
Yii::app()->clientScript->registerMetaTag($model->createPageDescription(), 'description');
Yii::app()->clientScript->registerMetaTag($model->createPageKeywords(), 'keywords');
Yii::app()->clientScript->registerLinkTag("canonical", NULL, 
Yii::app()->createUrl('town/alias', array(
        'name'          =>  $model->alias,
        'countryAlias'  =>  $model->country->alias,
        'regionAlias'   =>  $model->region->alias,
        )));


$this->breadcrumbs=array(
	'Страны'   =>  array('/region'),
        CHtml::encode($model->country->name) =>  array(
                        'region/country', 
                        'countryAlias'  => $model->country->alias,
                    ),
	CHtml::encode($model->region->name) =>  array(
                        'region/view', 
                        'regionAlias'   => $model->region->alias,
                        'countryAlias'  => $model->country->alias,
                    ),
);
?>

<?php
    $this->widget('zii.widgets.CBreadcrumbs', array(
        'homeLink'=>CHtml::link('100 Юристов',"/"),
        'separator'=>' / ',
        'links'=>$this->breadcrumbs,
     ));
?>


<h1>Юристы и Адвокаты г. <?php echo CHtml::encode($model->name); ?> (<?php echo CHtml::encode($model->region->name); ?>)</h1>
			

<?php if($model->description1):?>
    <div class="vert-margin30">
        <?php echo $model->description1;?>
    </div>
<?php endif;?>


<div class='flat-panel vert-margin20'>
    <div class='inside'>
        <?php foreach($questions as $question):?>
            <div class="row question-list-item">
                <div class="col-sm-10">
                    <p style="font-size:0.9em;">
                        
                        <?php echo CHtml::link($question['title'], Yii::app()->createUrl('question/view', array('id'=>$question['id'])));?>
                    </p>
                </div>
                
                <div class="col-sm-2 text-center">
                    <small>
                    <?php if($question['counter'] == 1) {
                        echo "<span class='text-success'> <span class='glyphicon glyphicon-ok'></span> Есть ответ</span>";
                    } elseif($question['counter']>1) {
                        echo "<span class='text-success'> <span class='glyphicon glyphicon-ok'></span> " . $question['counter'] . ' ' . CustomFuncs::numForms($question['counter'], 'ответ', 'ответа', 'ответов') . "</span>";
                    } elseif($question['counter'] == 0) {
                        echo "<span class='text-muted'>Нет ответа</span>";
                    }
                    ?>
                    </small>
            </div>
            </div>
        <?php endforeach;?>
<?php foreach($questions as $question):?>
        <!--
            <div class="row question-list-item <?php if($question['payed'] == 1):?> vip-question<?endif;?>">
                <div class="col-sm-9">
                    <p style="font-size:1.1em;">
                        <?php if($question['payed'] == 1){
                            echo "<span class='label label-primary'><abbr title='Вопрос с гарантией получения ответов'>VIP</abbr></span>";
                        }
                        ?>
                        <?php echo CHtml::link($question['title'], Yii::app()->createUrl('question/view', array('id'=>$question['id'])));?>
                    </p>
                </div>
                
                <div class="col-sm-3">
                
                <?php if($question['counter'] == 1) {
                    echo "<img src='/pics/2017/icon_checkmark.png' alt='Есть ответ' /> <span class='text-success'>Есть ответ</span>";
                } elseif($question['counter']>1) {
                    echo "<img src='/pics/2017/icon_checkmark.png' alt='Есть ответ' /> <span class='text-success'>" . $question['counter'] . ' ' . CustomFuncs::numForms($question['counter'], 'ответ', 'ответа', 'ответов') . "</span>";
                } elseif($question['counter'] == 0) {
                    echo "<span class='text-muted'>Нет ответа</span>";
                }
                ?>
                </span>
            </div>
            </div>
        -->
        <?php endforeach;?>
    </div>
</div>

<?php if(sizeof($model->companies)):?>

        <h3 class="header-block header-block-green">Юридические компании города</h3>
        <div class="header-block-green-arrow"></div>
            <div class="container-fluid">
                <div class="row">
                <?php 
                    $companyCounter = 0;
                    $companyLimit = 6;
                ?>
                <?php foreach($model->companies as $company):?>
                    <?php 
                        $companyCounter++;
                        if($companyCounter>$companyLimit) break;
                    ?>
                    <?php if($companyCounter%2 == 1) echo "<div class='row'>";?>

                    <div class="col-md-2">
                        <img src="<?php echo $company->getPhotoUrl('thumb');?>" alt="<?php echo CHtml::encode($company->name);?>" class="img-responsive" />
                    </div>
                    <div class="col-md-4">
                        <?php echo CHtml::link(CHtml::encode($company->name), Yii::app()->createUrl('yurCompany/view',array('id'=>$company->id)));?>
                    </div>
                    <?php if($companyCounter%2 == 0) echo "</div>";?>
                <?php endforeach;?>
                    <?php if($companyCounter%2 == 1 && $companyCounter != $companyLimit+1) echo "</div>";?>
                </div>
            </div>

<?php endif;?>

<?php if(is_array($closeTowns) && sizeof($closeTowns)):?>
<div class="flat-panel vert-margin30">
    
        <h3 class="header-block header-block-green">Соседние города</h3>
        <div class="header-block-green-arrow"></div>
        
        <div class="inside">
            <div class="row">
                <?php foreach($closeTowns as $town):?>
                    <div class="col-md-4">
                        <?php echo CHtml::link('<span class="glyphicon glyphicon-map-marker"></span>' . $town->name, 
                                Yii::app()->createUrl('town/alias', array(
                                            'name'          =>  $town->alias,
                                            'countryAlias'  =>  $town->country->alias,
                                            'regionAlias'   =>  $town->region->alias,
                                    )));?>
                    </div>    
                <?php endforeach;?>
            </div>
        
        </div>
    </div>
<?php endif;?>

<?php if($model->description2):?>
    <div class="vert-margin30">
            <?php echo $model->description2;?>
    </div>
<?php endif;?>

<br/>
<h3 class="header-block-light-grey"><strong> На ваши вопросы отвечают: </strong></h3>
    <div class='flat-panel inside'>
		
        <div class="row">
            
            <?php
                // выводим виджет с топовыми юристами
                $this->widget('application.widgets.TopYurists.TopYurists', array(
                    'cacheTime' =>  0,
                ));
            ?>
            
        </div>
    </div>
