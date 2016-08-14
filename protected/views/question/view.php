<?php
/* @var $this QuestionController */
/* @var $model Question */

$this->setPageTitle(CHtml::encode($model->title) . ". Консультация юриста и адвоката. ". Yii::app()->name);

if($model->description) {
    Yii::app()->clientScript->registerMetaTag($model->description, "Description");
} else {
    Yii::app()->clientScript->registerMetaTag(CHtml::encode(mb_substr($model->questionText, 0, 250,'utf-8')), 'description');
}

?>
 
<div itemscope itemtype="http://schema.org/Question">
     

<div class="panel gray-panel">
    <div class='panel-body'>
    
        <div  itemprop="author" itemscope itemtype="http://schema.org/Person">
            <p >
                <small>
                <?php if($model->authorName):?>
                    <span class="glyphicon glyphicon-user"></span>&nbsp;<span itemprop="name"><?php echo CHtml::encode($model->authorName); ?></span> &nbsp;&nbsp;
                <?php endif;?>
                <?php if($model->town):?>
                    <span class="glyphicon glyphicon-map-marker"></span>&nbsp;<?php echo CHtml::link(CHtml::encode($model->town->name),Yii::app()->createUrl('town/alias',
                        array(
                            'name'          =>  $model->town->alias,
                            'countryAlias'  =>  $model->town->country->alias,
                            'regionAlias'   =>  $model->town->region->alias,    
                            ))); ?> &nbsp;&nbsp;
                <?php endif;?>
                <?php if($model->categories):?>
                    <?php foreach($model->categories as $category):?>
                    <span class="glyphicon glyphicon-folder-open"></span>&nbsp;&nbsp;<?php echo CHtml::link(CHtml::encode($category->name),Yii::app()->createUrl('questionCategory/alias',array('name'=>CHtml::encode($category->alias))));?> &nbsp;&nbsp;
                    <?php endforeach;?>
                <?php endif;?>
                </small>
            </p>
        </div>
        <hr />
        
        <div>
        <?php if($model->title):?>
        <h1 itemprop="name"><?php echo CHtml::encode($model->title); ?></h1>
        <?php endif;?>
        </div>

    <p itemprop="text">
        <?php echo nl2br(CHtml::encode($model->questionText));?>
    </p>
	


    <?php $this->widget('zii.widgets.CListView', array(
            'dataProvider'  =>  $answersDataProvider,
            'itemView'      =>  'application.views.answer._view',
            'emptyText'     =>  '<p class="alert alert-info">Юристы пока не дали ответ...</p>',
            'summaryText'   =>  '',
            'pager'         =>  array('class'=>'GTLinkPager') //we use own pager with russian words

    )); ?>

	<br/>
	<noindex>
	<div>
	<p>
		<script type="text/javascript" src="//yastatic.net/es5-shims/0.0.2/es5-shims.min.js" charset="utf-8"></script>
		<script type="text/javascript" src="//yastatic.net/share2/share.js" charset="utf-8"></script>
		<div class="ya-share2" data-services="vkontakte,facebook,odnoklassniki,moimir,gplus,twitter,linkedin,lj,surfingbird,tumblr"></div>
	</p>
	</div>
	</noindex>
	
		
        <?php if(Yii::app()->user->isGuest):?>
    <div class="alert alert-success">
		<strong>Внимание!</strong> Если вы специалист в области права вы можете дать ответ на этот вопрос пройдя нехитрую процедуру <a href="/user/create/" class="alert-link" >регистрации</a> и подтверждения вашей квалификации.
	</div>	   
        <?php endif;?>
    </div>
</div>
    
    
</div> <!-- Question --> 

<?php if(Yii::app()->user->role == User::ROLE_JURIST || Yii::app()->user->role == User::ROLE_OPERATOR):?>
<div class="panel gray-panel">
    <div class='panel-body'>
        <h2>Ваш ответ</h2>
        <?php $this->renderPartial('application.views.answer._form', array('model'=>$answerModel));?>
    </div>
</div>
<?php endif;?>


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

<?php if($similarDataProvider->totalItemCount > 0):?>

<div class="panel gray-panel">
    <div class='panel-body'>
        <h4>Вопросы со схожей тематикой:</h4> 
        <?php $this->widget('zii.widgets.CListView', array(
                'dataProvider'  =>  $similarDataProvider,
                'itemView'      =>  'application.views.question._viewShort',
                'emptyText'     =>  'Не найдено ни одного вопроса',
                'summaryText'   =>  '',

        )); ?>
        
    </div>
</div>
<?php endif;?>


<div class="panel gray-panel">
    <div class='panel-body'>
       <h4>На ваши вопросы отвечают:</h4> 
    
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
