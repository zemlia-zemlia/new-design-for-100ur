<?php
/* @var $this QuestionController */
/* @var $model Question */

$this->setPageTitle(CHtml::encode($model->title) . ". Консультация юриста и адвоката. ". Yii::app()->name);
Yii::app()->clientScript->registerLinkTag("canonical",NULL, Yii::app()->createUrl('question/view', array('id'=>$model->id)));

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
        
    <div class="flat-panel vert-margin30">
        <div>
        <?php if($model->title):?>
        <h1 itemprop="name" class="header-block header-block-light-grey"><?php echo CHtml::encode($model->title); ?></h1>
        <?php endif;?>
        </div>

    
        <div itemprop="text" class="inside">
            <?php echo nl2br(CHtml::encode($model->questionText));?>
        </div>

        <div class="inside right-align">
            <script src="//yastatic.net/es5-shims/0.0.2/es5-shims.min.js"></script>
            <script src="//yastatic.net/share2/share.js"></script>
            <div class="ya-share2" data-services="vkontakte,facebook,odnoklassniki,twitter,viber,whatsapp"></div>
        </div>
    </div>

    <?php $this->widget('zii.widgets.CListView', array(
            'dataProvider'  =>  $answersDataProvider,
            'itemView'      =>  'application.views.answer._view',
            'emptyText'     =>  '<p class="text-muted inside">Юристы пока не дали ответ...</p>',
            'summaryText'   =>  '',
            'pager'         =>  array('class'=>'GTLinkPager'), //we use own pager with russian words
            'viewData'      =>  array(
                'commentModel'          =>  $commentModel,
                ),
    )); ?>    
    
    <br/>    
    

<?php if(Yii::app()->user->role == User::ROLE_ROOT || ($model->authorId == Yii::app()->user->id && ($model->price==0 || $model->payed == 0))):?>


		<h3 class="header-block-light-grey"><strong> 100% гарантия получения ответа </strong></h3>

		<table class="table center-align small table-bordered alert alert-info">
                <tr>
                    <th class="center-align" style="width: 33%">Бронза</th>
                    <th class="center-align" style="width: 33%">Серебро</th>
                    <th class="center-align" style="width: 33%">Золото</th>
                </tr>
                <tr>

                </tr>
                <tr  class="warning">
                    <td>1<br/><span class="mutted">гарантированный ответ</span></td>
                    <td>3<br/><span class="mutted">гарантированных ответа</span></td>
                    <td>5<br/><span class="mutted">гарантированных ответов</span></td>
                </tr>
                <tr>
                    <td>Один гарантированный ответ одного из проверенных юристов портала</td>
                    <td>Три гарантированных ответа юристов, позволят Вам понять, как решить проблему</td>
                    <td>Минимум пять гарантированных ответов юристов. Мнения нескольких юристов. Гарантия полного и подробного разбора ситуации.</td>
                </tr>
                <tr class="success">
                    <td>125 руб.</td>
                    <td>295 руб.</td>
                    <td>455 руб.</td>
                </tr>
                <tr>
                    <td><?php echo CHtml::link('Выбрать', Yii::app()->createUrl('question/upgrade', array('id'=>$model->id, 'level'=>Question::LEVEL_1)), array('class'=>'btn btn-warning btn-block'));?></td>
                    <td><?php echo CHtml::link('Выбрать', Yii::app()->createUrl('question/upgrade', array('id'=>$model->id, 'level'=>Question::LEVEL_2)), array('class'=>'btn btn-warning btn-block'));?></td>
                    <td><?php echo CHtml::link('Выбрать', Yii::app()->createUrl('question/upgrade', array('id'=>$model->id, 'level'=>Question::LEVEL_3)), array('class'=>'btn btn-warning btn-block'));?></td>
                </tr>
            </table>

			
			
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

<?php endif;?>    


      
        
<?php if(Yii::app()->user->isGuest):?>
            <!--<div class="alert alert-success">
                    <strong>Внимание!</strong> Если вы специалист в области права вы можете дать ответ на этот вопрос пройдя нехитрую процедуру <a href="/user/create/" class="alert-link" >регистрации</a> и подтверждения вашей квалификации.
            </div>	-->    
<?php endif;?>    
    
</div> <!-- Question --> 

<?php if(Yii::app()->user->role == User::ROLE_JURIST && !in_array(Yii::app()->user->id, $answersAuthors)):?>
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


<?php if(!(Yii::app()->user->role == User::ROLE_JURIST || ($model->authorId == Yii::app()->user->id))):?>

	<div class="form-container">		
            <div class="center-align">
            <?php
                // выводим виджет с номером 8800
                $this->widget('application.widgets.Hotline.HotlineWidget', array(
                ));
            ?>		
            </div>
            <div class="form-container-content">
                <h3 class="center-align header-block header-block-light-grey">Задать вопрос on-line<br/>доступно для ВСЕХ регионов РФ</h3>
                                
                <?php echo $this->renderPartial('application.views.question._formBrief', array(
                    'newQuestionModel'  =>  $newQuestionModel,
                ));?>
                              
            </div>
	</div>

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
                    'cacheTime' =>  0,
                ));
            ?>
            
        </div>
    </div>
</div>        
