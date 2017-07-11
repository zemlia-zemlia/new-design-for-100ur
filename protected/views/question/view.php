<?php
/* @var $this QuestionController */
/* @var $model Question */

$this->setPageTitle(CHtml::encode($model->title) . ". Консультация юриста и адвоката. ". Yii::app()->name);
Yii::app()->clientScript->registerLinkTag("canonical",NULL, Yii::app()->createUrl('question/view', array('id'=>$model->id)));

Yii::app()->clientScript->registerMetaTag(CHtml::encode(mb_substr($model->questionText, 0, 250,'utf-8')), 'description');

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
                <span class="label label-warning"><span class='glyphicon glyphicon-ruble'></span></span>
                <?php endif;?>
            
                <small>
                <?php if($model->publishDate):?>
                    <span class="glyphicon glyphicon-calendar"></span>&nbsp;<time itemprop="dateCreated" datetime="<?php echo $model->publishDate;?>"><?php echo CustomFuncs::niceDate($model->publishDate, false); ?></time> &nbsp;&nbsp;
                <?php endif;?>
                    
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
                    <span class="glyphicon glyphicon-folder-open"></span>&nbsp;&nbsp;<?php echo CHtml::link(CHtml::encode($category->name),Yii::app()->createUrl('questionCategory/alias', $category->getUrl()));?> &nbsp;&nbsp;
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
            'emptyText'     =>  '<p class="text-muted inside">Ответов на этот вопрос пока нет...</p>',
            'summaryText'   =>  '',
            'pager'         =>  array('class'=>'GTLinkPager'), //we use own pager with russian words
            'viewData'      =>  array(
                'commentModel'          =>  $commentModel,
                ),
    )); ?>    
    
    <br/>    
    


      
        
<?php if(Yii::app()->user->isGuest):?>
            <div class="alert alert-success">
                    <strong>Внимание!</strong> Если вы специалист в области права вы можете дать ответ на этот вопрос пройдя нехитрую процедуру <a href="/user/create/" class="alert-link" >регистрации</a> и подтверждения вашей квалификации.
            </div>  
<?php endif;?>    
    
</div> <!-- Question --> 

<?php if(in_array(Yii::app()->user->role, array(User::ROLE_JURIST, User::ROLE_ROOT)) && !in_array(Yii::app()->user->id, $answersAuthors)):?>

    
        
        <?php if(Yii::app()->user->isVerified || Yii::app()->user->role == User::ROLE_ROOT):?>
            <div class='flat-panel inside'>
            <h2 class="header-block-light-grey" >Ваш ответ:</h2>
            <p class="text-muted inside">
                    При ответах на вопросы соблюдайте, пожалуйста, правила сайта. Обратите внимание, что реклама в тексте ответа запрещена, контактные данные можно указывать только в своем профиле. Запрещается полное или частичное копирование текста ответов с других ресурсов.
            </p>
		
            <?php $this->renderPartial('application.views.answer._form', array('model'=>$answerModel));?>
            </div>
        <?php else:?>
            <?php if(empty($lastRequest)):?>
                <div class="alert alert-danger">
                    <p>
                    Вы не можете отвечать на вопросы, пока не подтвердили свою квалификацию. 
                    Вы можете сделать это в настройках своего профиля.
                    </p><br />
                    <?php echo CHtml::link('Подтвердить квалификацию', Yii::app()->createUrl('userStatusRequest/create'), array('class'=>'btn btn-default'));?>
                </div>
            <?php endif;?>
        <?php endif;?>
				
    

<?php endif;?>


<?php if(!(Yii::app()->user->role == User::ROLE_JURIST || ($model->authorId == Yii::app()->user->id))):?>

	<div class="flat-panel inside">		
            <div class="center-align">
            <?php
                // выводим виджет с номером 8800
                $this->widget('application.widgets.Hotline.HotlineWidget', array(
                    'showAlways'    =>  true,
                ));
            ?>		
            </div>
            <div class="form-container-content form-container ">
                <h3 class="center-align header-block header-block-light-grey">Задать вопрос on-line<br/>доступно для ВСЕХ регионов РФ</h3>
                                
                <?php echo $this->renderPartial('application.views.question._formBrief', array(
                    'newQuestionModel'  =>  $newQuestionModel,
                ));?>
                              
            </div>
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

