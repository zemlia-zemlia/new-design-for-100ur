<?php
/* @var $this QuestionCategoryController */
/* @var $model QuestionCategory */

$this->setPageTitle(CHtml::encode($model->name) . ". Консультация юриста и адвоката. ". Yii::app()->name);

Yii::app()->clientScript->registerMetaTag("Получите бесплатную консультацию юриста. Ответы квалифицированных юристов на вопросы тематики " . CHtml::encode($model->name), 'description');


?>

<h1 class="vert-margin30"><?php echo CHtml::encode($model->name);?></h1>


<div class="question-form-wrapper">
<h3>Задайте вопрос юристу бесплатно</h3>
<?php
    $this->renderPartial('application.views.question._formSimple', array(
            'model'=>$questionModel,
        ));
?>
</div>

<h2>Вопросы юристу на тему &laquo;<?php echo CHtml::encode($model->name);?>&raquo;</h2>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'  =>  $questionsDataProvider,
	'itemView'      =>  'application.views.question._view',
        'viewData'      =>  array(
            'hideCategory'  =>  true,
        ),
        'emptyText'     =>  'Не найдено ни одного вопроса',
        'summaryText'   =>  '',
        'pager'         =>  array('class'=>'GTLinkPager') //we use own pager with russian words
)); ?>
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