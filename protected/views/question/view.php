<?php
/* @var $this QuestionController */
/* @var $model Question */

$this->setPageTitle(CHtml::encode($model->title) . ". Консультация юриста и адвоката. ". Yii::app()->name);

if($model->description) {
    Yii::app()->clientScript->registerMetaTag($model->description, "Description");
} else {
    Yii::app()->clientScript->registerMetaTag(CHtml::encode(mb_substr($model->questionText, 0, 250,'utf-8')), 'description');
}

$this->breadcrumbs=array(
	CHtml::encode($model->category->name)   =>  array('questionCategory/alias','name'=>CHtml::encode($model->category->alias)),
	CHtml::encode($model->title),
);

?>

<?php if($model->category):?>
    <?php
    $this->widget('zii.widgets.CBreadcrumbs', array(
        'homeLink'=>CHtml::link('Консультация юриста',"/"),
        'separator'=>' &rarr; ',
        'links'=>$this->breadcrumbs,
     ));
     ?>
<?php endif;?>

 <hr/>
 
<div itemscope itemtype="http://schema.org/Question">
     

<div class="panel">
    <div class='panel-body'>
    
        <div itemprop="author" itemscope itemtype="http://schema.org/Person">
            <p>
                <?php if($model->authorName):?>
                    <img src='/pics/2015/icon_user.png' alt='' />&nbsp;<span itemprop="name"><?php echo CHtml::encode($model->authorName); ?></span> &nbsp;&nbsp;
                <?php endif;?>
                <?php if($model->town):?>
                    <img src='/pics/2015/icon_marker.png' alt='' />&nbsp;<?php echo CHtml::link(CHtml::encode($model->town->name),Yii::app()->createUrl('town/alias',array('name'=>CHtml::encode($model->town->alias)))); ?> &nbsp;&nbsp;
                <?php endif;?>
                <?php if($model->category):?>
                    <img src='/pics/2015/icon_folder.png' alt='' />&nbsp;&nbsp;<?php echo CHtml::link(CHtml::encode($model->category->name),Yii::app()->createUrl('questionCategory/alias',array('name'=>CHtml::encode($model->category->alias))));?> &nbsp;&nbsp;
                <?php endif;?>
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
            'emptyText'     =>  'Не найдено ни одного ответа',
            'summaryText'   =>  '',
            'pager'         =>  array('class'=>'GTLinkPager') //we use own pager with russian words

    )); ?>


    </div>
</div>
    
    
</div> <!-- Question --> 

<? /*
<div class="vert-margin30 center-align">
    <?php echo CHtml::link('Получить консультацию юриста', Yii::app()->createUrl('question/create'), array('class'=>'btn btn-warning btn-lg','rel'=>'nofollow','onclick'=>'yaCounter26550786.reachGoal("submit_after_button"); return true;')); ?>
</div>
*/?>

<h3>При поддержке</h3>

<div class="panel">
    <div class="panel-body">
        <div class="row">
            <div class="col-md-6 col-sm-6 center-align">
                <img class="img-responsive center-block" alt="При поддержке правительства РФ" src="/pics/pravitelstvo.png">
                <p class="center-align">Правительство РФ
                </p>
            </div>

            <div class="col-md-6 col-sm-6 center-align"> 
                <img class="img-responsive center-block" alt="При поддержке Министерства Юстиции" src="/pics/minyust.png"> 
                <p class="center-align">Министерство Юстиции</p>
            </div>
        </div>
    </div>
</div>
<!-- Форма --> 
<noindex>
            <div class="form-container">
                <h2 class="center-align">Задать вопрос</h2>
                                
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
                        <div class="form-info-item">
                            <p><span class="form-icon" style="background-position: 0 0;"></span><strong>Это быстро</strong><br />
                            Вы получите ответ через 15 минут</p>
                        </div>
                        <div class="form-info-item">
                            <p><span class="form-icon" style="background-position: -32px 0;"></span><strong>Безопасно</strong><br />
                            Только аккредитованные юристы</p>
                        </div>
                        <div class="form-info-item">
                            <p><span class="form-icon" style="background-position: -67px 0;"></span><strong>Без спама</strong><br />
                            Мы никогда не рассылаем рекламу</p>
                        </div>
                    </div>
                </div> 
                <div class="row">
                    <div class="col-md-7">
                        <div class="form-group">
                            <label>Ваше имя *</label>
                            <?php echo $form->textField($newQuestionModel,'authorName', array('class'=>'form-control', 'placeholder'=>'Иванов Иван')); ?>
                            <?php echo $form->error($newQuestionModel,'authorName'); ?>
                        </div>
                    </div>
                    <div class="col-md-5">
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
<h3>Похожие вопросы</h3>
<div class="panel">
    <div class='panel-body'>
        
        <?php $this->widget('zii.widgets.CListView', array(
                'dataProvider'  =>  $similarDataProvider,
                'itemView'      =>  'application.views.question._viewShort',
                'emptyText'     =>  'Не найдено ни одного вопроса',
                'summaryText'   =>  '',

        )); ?>
        
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