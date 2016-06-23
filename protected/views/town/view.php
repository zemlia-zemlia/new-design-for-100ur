<?php
/* @var $this TownController */
/* @var $model Town */

$pageTitle = $model->createPageTitle();
        
if(isset($_GET) && (int)$_GET['Question_page']) {
    $pageNumber = (int)$_GET['Question_page'];
    $pagesTotal = ceil($dataProvider->totalItemCount / $dataProvider->pagination->getPageSize());
    $pageTitle .= '. Страница ' . $pageNumber . ' из ' . $pagesTotal . '. ';
}
$this->setPageTitle($pageTitle);
Yii::app()->clientScript->registerMetaTag($model->createPageDescription(), 'description');
Yii::app()->clientScript->registerMetaTag($model->createPageKeywords(), 'keywords');
Yii::app()->clientScript->registerLinkTag("canonical",NULL,"http://".$_SERVER['SERVER_NAME'].Yii::app()->createUrl('/town/alias', array('name'=>$model->alias)));

?>


<div 
    <?php if($model->photo != '') { 
            echo "class='town-cover' style='background-image:url(" . $model->getPhotoUrl() . ")'";
        }
    ?>
    >
     
			<h1>Консультация юриста <?php echo CHtml::encode($model->name); ?></h1>
 
</div>

<?php if($model->description1):?>
    <div class="panel">
        <div class="panel-body">
            <?php echo $model->description1;?>
        </div>
    </div>
<?php endif;?>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'  =>  $dataProvider,
	'itemView'      =>  'application.views.question._view',
        'emptyText'     =>  'Не найдено ни одного вопроса',
        'summaryText'   =>  '',
        'ajaxUpdate'    =>  false,
        'pager'         =>  array('class'=>'GTLinkPager') //we use own pager with russian words

)); ?>

<?php if(sizeof($model->companies)):?>
    <div class="panel">
        <div class="panel-body">
        <h3>Юридические компании города</h3>
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

                    <div class="col-md-2"><img src="<?php echo $company->getPhotoUrl('thumb');?>" alt="" class="img-responsive" /></div>
                    <div class="col-md-4">
                        <?php echo CHtml::link(CHtml::encode($company->name), Yii::app()->createUrl('yurCompany/view',array('id'=>$company->id)));?>
                    </div>
                    <?php if($companyCounter%2 == 0) echo "</div>";?>
                <?php endforeach;?>
                </div>
            </div>
        </div>
    </div>
<?php endif;?>

<?php if(is_array($closeTowns) && sizeof($closeTowns)):?>
<div class="panel">
    <div class="panel-body">
        <h3>Соседние города</h3>
        <div class="row">
            <?php foreach($closeTowns as $town):?>
                <div class="col-md-4">
                    <?php echo CHtml::link('<span class="glyphicon glyphicon-map-marker"></span>' . $town->name, Yii::app()->createUrl('town/alias', array('name'=>$town->alias)));?>
                </div>    
            <?php endforeach;?>
            </div>
        
    </div>
</div>
<?php endif;?>

<div class="panel">
	<div class="panel-body">
		<h2>Юристы и Адвокаты из/в <?php echo CHtml::encode($model->name); ?> отвечают на ваши вопросы</h2>
	</div>
</div>

<?php if($model->description2):?>
    <div class="panel">
        <div class="panel-body">
            <?php echo $model->description2;?>
        </div>
    </div>
<?php endif;?>

<noindex>
<div class="vert-margin30">
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
</noindex>


