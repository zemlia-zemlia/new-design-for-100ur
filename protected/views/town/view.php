<?php
/* @var $this TownController */
/* @var $model Town */
$this->setPageTitle("Консультация юриста в городе " . CHtml::encode($model->name) . ". " . Yii::app()->name);
Yii::app()->clientScript->registerMetaTag("Консультация юриста по всем отраслям права в городе " . CHtml::encode($model->name) . ", только профессиональные юристы и адвокаты.", 'description');

?>

<h1>Консультация юриста <?php echo CHtml::encode($model->name); ?></h1>

<div class="question-form-wrapper">
<h3>Задайте вопрос юристу бесплатно</h3>
<?php
    $this->renderPartial('application.views.question._formSimple', array(
            'model'=>$questionModel,
        ));
?>
</div>

<?php if($model->description1):?>
    <div class="vert-margin30"> 
        <?php echo $model->description1;?>
    </div>
<?php endif;?>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'  =>  $dataProvider,
	'itemView'      =>  'application.views.question._view',
        'emptyText'     =>  'Не найдено ни одного вопроса',
        'summaryText'   =>  '',
        'pager'         =>  array('class'=>'GTLinkPager') //we use own pager with russian words

)); ?>

<?php if($model->description2):?>
    <div class="vert-margin30"> 
        <?php echo $model->description2;?>
    </div>
<?php endif;?>

<div class="vert-margin30">
<h3>На ваши вопросы отвечают:</h3>
    <div class="row">
        <div class="col-md-3 col-sm-3 center-align well">
            <img src="/pics/yurist1.png" alt="При поддержке правительства РФ" class="img-responsive center-block" />
            <p class="center-align"><b>Кудряшов Алексей Генадиевич</b><br />
			<small> Семейное право<br/>
			Уголовное право<br/>
			Наследство<br/>
			ЗПП<br/>
			Налоги <br/>
			Банковское право<br/></small>
                </p>
                <a href="/question/create/?utm_source=100yuristov&utm_campaign=yuristi&utm_medium=button&utm_content=kudryashov" class="btn btn-primary" rel="nofollow">Получить консультацию</a>
        </div>

        <div class="col-md-3 col-sm-3 center-align well"> 
            <img src="/pics/yurist3.png" alt="При поддержке Министерства Юстиции" class="img-responsive center-block" /> 
            <p class="center-align"><b>Самойлов Николай Николаевич</b><br />
			<small> Семейное право<br/>
			Уголовное право<br/>
			Трудовое право<br/>
			Договорные отношения<br/>
			Налогое право <br/>
			</small>
                </p>
                <a href="/question/create/?utm_source=100yuristov&utm_campaign=yuristi&utm_medium=button&utm_content=samoilov" class="btn btn-primary" rel="nofollow">Получить консультацию</a>

        </div>
		
		<div class="col-md-3 col-sm-3 center-align well"> 
            <img src="/pics/yurist2.png" alt="При поддержке Министерства Юстиции" class="img-responsive center-block" /> 
            <p class="center-align"><b>Штуцер Максим Федорович</b><br />
			<small> Семейное право<br/>
			Уголовное право<br/>
			Корпоративное право<br/>
			Договорные отношения<br/>
			Банковская деятельность<br/>
			Кредиты</small>
            </p>
                <a href="/question/create/?utm_source=100yuristov&utm_campaign=yuristi&utm_medium=button&utm_content=shtutzer" class="btn btn-primary" rel="nofollow">Получить консультацию</a>
            
        </div>
		
		<div class="col-md-3 col-sm-3 center-align well"> 
            <img src="/pics/yurist4.png" alt="При поддержке Министерства Юстиции" class="img-responsive center-block" /> 
            <p class="center-align"><b>Тихонова Анастасия Викторовна</b><br />
			<small> Семейное право<br/>
			Уголовное право<br/>
			Корпоративное право<br/>
			Договорные отношения<br/>
			Банковская деятельность<br/></small>
            </p>
            <a href="/question/create/?utm_source=100yuristov&utm_campaign=yuristi&utm_medium=button&utm_content=tikhonova" class="btn btn-primary" rel="nofollow">Получить консультацию</a>

        </div>
     
    </div>
</div>

