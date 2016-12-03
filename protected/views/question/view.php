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
     


    
        <div  itemprop="author" itemscope itemtype="http://schema.org/Person">
            <p>
                <?php if($model->price!=0 && $model->payed == 1):?>
                <span class="label label-primary">VIP</span>
                <?php endif;?>
            
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
        
        <div>
        <?php if($model->title):?>
        <h1 itemprop="name" class="header-block header-block-light-grey vert-margin30"><?php echo CHtml::encode($model->title); ?></h1>
        <?php endif;?>
        </div>

    <p itemprop="text">
        <?php echo nl2br(CHtml::encode($model->questionText));?>
    </p>


    <?php $this->widget('zii.widgets.CListView', array(
            'dataProvider'  =>  $answersDataProvider,
            'itemView'      =>  'application.views.answer._view',
            'emptyText'     =>  '<p class="alert alert-info gray-panel">Юристы пока не дали ответ...</p>',
            'summaryText'   =>  '',
            'pager'         =>  array('class'=>'GTLinkPager'), //we use own pager with russian words
            'viewData'      =>  array(
                'commentModel'          =>  $commentModel,
                ),
    )); ?>    
    
    
    
    

<?php if(Yii::app()->user->role == User::ROLE_ROOT || ($model->authorId == Yii::app()->user->id && ($model->price==0 || $model->payed == 0))):?>


            <h3 class="vert-margin30"> 100% гарантия получения ответа </h3>
            
            <div class="alert alert-info gray-panel">
                <h4>Вы экономите</h4>

                <div class="row center-align vert-margin30">
                    <div class="col-sm-4">
                        <h2><span class="glyphicon glyphicon-road"></span></h2>
                        <p>
                            4 часа на дорогу к юристу
                        </p>
                    </div>
                    <div class="col-sm-4">
                        <h2><span class="glyphicon glyphicon-ruble"></span></h2>
                        <p>
                            <strong>Минимум 1000 рублей</strong> за консультацию в офисе
                        </p>
                    </div>
                    <div class="col-sm-4">
                        <h2><span class="glyphicon glyphicon-time"></span></h2>
                        <p>
                            8 часов на поиск ответа в Интернете
                        </p>
                    </div>
                </div>
            </div>
            
		 <table class="table center-align small table-bordered alert alert-info">
                <tr>
                    <th></th>
                    <th class="center-align">Бесплатный</th>
                    <th class="center-align">Бронзовый</th>
                    <th class="center-align">Серебрянный</th>
                    <th class="center-align">Золотой</th>
                </tr>
                <tr>
                    <td>Гарантия ответа</td>
                    <td><span class="glyphicon glyphicon-remove"></span></td>
                    <td><span class="glyphicon glyphicon-ok"></span></td>
                    <td><span class="glyphicon glyphicon-ok"></span></td>
                    <td><span class="glyphicon glyphicon-ok"></span></td>
                </tr>
                <tr  class="warning">
                    <td>Гарантировано ответов</td>
                    <td><span class="glyphicon glyphicon-remove"></span></td>
                    <td>1</td>
                    <td>3</td>
                    <td>5</td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td>Один гарантированный ответ квалифицированного юриста</td>
                    <td>Три гарантированных ответа юристов, позволят Вам понять, как решить проблему</td>
                    <td>Минимум пять гарантированных ответов юристов. Мнения нескольких юристов. Гарантия полного и подробного разбора ситуации.</td>
                </tr>
                <tr class="success">
                    <td>Цена</td>
                    <td>0 руб.</td>
                    <td>99 руб.</td>
                    <td>199 руб.</td>
                    <td>299 руб.</td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td><?php echo CHtml::link('Выбрать', Yii::app()->createUrl('question/upgrade', array('id'=>$model->id, 'level'=>Question::LEVEL_1)), array('class'=>'btn btn-success btn-block'));?></td>
                    <td><?php echo CHtml::link('Выбрать', Yii::app()->createUrl('question/upgrade', array('id'=>$model->id, 'level'=>Question::LEVEL_2)), array('class'=>'btn btn-success btn-block'));?></td>
                    <td><?php echo CHtml::link('Выбрать', Yii::app()->createUrl('question/upgrade', array('id'=>$model->id, 'level'=>Question::LEVEL_3)), array('class'=>'btn btn-success btn-block'));?></td>
                </tr>
            </table>


<?php endif;?>    


      
        
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

<?php if(Yii::app()->user->role == User::ROLE_JURIST || Yii::app()->user->role == User::ROLE_OPERATOR || Yii::app()->user->role == User::ROLE_CALL_MANAGER):?>
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


<?php if(!(Yii::app()->user->role == User::ROLE_JURIST || Yii::app()->user->role == User::ROLE_OPERATOR || Yii::app()->user->role == User::ROLE_CALL_MANAGER || ($model->authorId == Yii::app()->user->id))):?>

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
                                <?php echo CHtml::submitButton($newQuestionModel->isNewRecord ? 'Задать вопрос юристу' : 'Сохранить', array('class'=>'button  button-blue-gradient btn-block', 'onclick'=>'yaCounter26550786.reachGoal("simple_form_submit"); return true;')); ?>
                        </div>
					</div>
                </div> 
                <?php $this->endWidget(); ?>
                              
            </div>
</noindex>
<!-- Конец формы --> 
<?php endif;?>



<?php if(sizeof($questionsSimilar) > 0):?>

<div class="flat-panel">
    
        <h3 class="header-block header-block-green">Вопросы со схожей тематикой:</h3> 
        <div class="header-block-green-arrow"></div>

        <div class='inside'>
        <?php foreach($questionsSimilar as $question):?>
        <div class="row">
            
            <div class="col-sm-9">
                <p><?php echo CHtml::link(CHtml::encode($question['title']), Yii::app()->createUrl('question/view', array('id'=>$question['id'])));?></p>
            </div>
            <div class="col-sm-3">
                <img src='/pics/2017/icon_checkmark.png' alt='Есть ответ' /> <span class='text-success'>
                <?php if($question['counter'] == 1) {
                    echo "Есть ответ";
                } elseif($question['counter']>1) {
                    echo $question['counter'] . ' ' . CustomFuncs::numForms($question['counter'], 'ответ', 'ответа', 'ответов');
                }
                ?>
                </span>
            </div>
        </div>
        
        <?php endforeach;?> 
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
