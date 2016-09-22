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

<?php if($justPublished == true):?>
<div class="alert alert-warning gray-panel" role="alert">
        <h4>Спасибо, <?php echo CHtml::encode(Yii::app()->user->name);?>!</h4>
        <p class="text-success">
            <strong><span class="glyphicon glyphicon-ok"></span> Ваш вопрос опубликован</strong>. Теперь юристы смогут дать Вам ответ. <br />
            <strong><span class="glyphicon glyphicon-ok"></span> Ваш Email подтвержден</strong>. На него Вы будете получать уведомления о новых ответах. <br />
            
        </p>
</div>
<?php endif;?>
 
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
        
    <noindex>
        <div style="border-top:#ccc 1px solid;">
	<p>
            Опубликовать вопрос 
		<script type="text/javascript" src="//yastatic.net/es5-shims/0.0.2/es5-shims.min.js" charset="utf-8"></script>
		<script type="text/javascript" src="//yastatic.net/share2/share.js" charset="utf-8"></script>
		<span class="ya-share2" data-services="vkontakte,facebook,odnoklassniki,moimir,gplus,twitter,linkedin,lj,surfingbird,tumblr"></span>
	</p>
	</div>
	</noindex>
	
    </div>
</div>
    

<?php if($model->authorId == Yii::app()->user->id && ($model->price==0 || $model->payed == 0)):?>

    <div class="panel panel-default">
        <div class="panel-body">
            <h3>Сделать VIP вопросом</h3>
            
            <table class="table center-align">
                <tr>
                    <th></th>
                    <th>Бесплатный</th>
                    <th>Бронзовый</th>
                    <th>Серебрянный</th>
                    <th>Золотой</th>
                </tr>
                <tr>
                    <td>Гарантия ответа</td>
                    <td><span class="glyphicon glyphicon-remove"></span></td>
                    <td><span class="glyphicon glyphicon-ok"></span></td>
                    <td><span class="glyphicon glyphicon-ok"></span></td>
                    <td><span class="glyphicon glyphicon-ok"></span></td>
                </tr>
                <tr>
                    <td>Гарантировано ответов</td>
                    <td><span class="glyphicon glyphicon-remove"></span></td>
                    <td>1</td>
                    <td>3</td>
                    <td>5</td>
                </tr>
                <tr>
                    <td></td>
                    <td><small>Нет гарантии ответа юриста.</small></td>
                    <td><small>Один гарантированный ответ квалифицированного юриста</small></td>
                    <td><small>Три гарантированных ответа юристов, позволят Вам понять, как решить проблему</small></td>
                    <td><small>Минимум пять гарантированных ответов юристов. Мнения нескольких юристов. Гарантия полного и подробного разбора ситуации.</small></td>
                </tr>
                <tr>
                    <td>Цена</td>
                    <td>0 руб.</td>
                    <td>99 руб.</td>
                    <td>199 руб.</td>
                    <td>299 руб.</td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td><?php echo CHtml::link('Оплатить', Yii::app()->createUrl('question/upgrade', array('id'=>$model->id, 'level'=>Question::LEVEL_1)), array('class'=>'btn btn-success btn-block'));?></td>
                    <td><?php echo CHtml::link('Оплатить', Yii::app()->createUrl('question/upgrade', array('id'=>$model->id, 'level'=>Question::LEVEL_2)), array('class'=>'btn btn-success btn-block'));?></td>
                    <td><?php echo CHtml::link('Оплатить', Yii::app()->createUrl('question/upgrade', array('id'=>$model->id, 'level'=>Question::LEVEL_3)), array('class'=>'btn btn-success btn-block'));?></td>
                </tr>
            </table>
        </div>
    </div>

<?php endif;?>    

    <?php $this->widget('zii.widgets.CListView', array(
            'dataProvider'  =>  $answersDataProvider,
            'itemView'      =>  'application.views.answer._view',
            'emptyText'     =>  '<p class="alert alert-info gray-panel">Юристы пока не дали ответ...</p>',
            'summaryText'   =>  '',
            'pager'         =>  array('class'=>'GTLinkPager') //we use own pager with russian words

    )); ?>
      
        
<?php if(Yii::app()->user->isGuest):?>
    <div class="panel gray-panel">
        <div class='panel-body'>


            <div class="alert alert-success">
                    <strong>Внимание!</strong> Если вы специалист в области права вы можете дать ответ на этот вопрос пройдя нехитрую процедуру <a href="/user/create/" class="alert-link" >регистрации</a> и подтверждения вашей квалификации.
            </div>	   

        </div>
    </div>
<?php endif;?>    
    
</div> <!-- Question --> 

<?php if(Yii::app()->user->role == User::ROLE_JURIST || Yii::app()->user->role == User::ROLE_OPERATOR):?>
<div class="panel gray-panel">
    <div class='panel-body'>
        
        <?php if(Yii::app()->user->isVerified):?>
            <h2>Ваш ответ</h2>
            <?php $this->renderPartial('application.views.answer._form', array('model'=>$answerModel));?>
        <?php else:?>
            <div class="alert alert-warning">
                <p>
                Вы не можете отвечать на вопросы, пока не подтвердили свою квалификацию. 
                Вы можете сделать это в настройках своего профиля.
                </p><br />
                <?php echo CHtml::link('Перейти в настройки', Yii::app()->createUrl('user/update', array('id'=>Yii::app()->user->id)), array('class'=>'btn btn-primary'));?>
            </div>
        <?php endif;?>
    </div>
</div>
<?php endif;?>


<?php if(!(Yii::app()->user->role == User::ROLE_JURIST || Yii::app()->user->role == User::ROLE_OPERATOR || ($model->authorId == Yii::app()->user->id))):?>

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
<?php endif;?>



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
            
            <?php
                // выводим виджет с топовыми юристами
                $this->widget('application.widgets.TopYurists.TopYurists', array(
                    'cacheTime' =>  600,
                ));
            ?>
            
        </div>
    </div>
</div>        
