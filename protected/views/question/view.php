<?php
/* @var $this QuestionController */
/* @var $model Question */

$this->setPageTitle(CHtml::encode($model->title) . ". Консультация юриста и адвоката. ". Yii::app()->name);
Yii::app()->clientScript->registerLinkTag("canonical",NULL, Yii::app()->createUrl('question/view', array('id'=>$model->id)));

Yii::app()->clientScript->registerMetaTag(CHtml::encode(mb_substr($model->questionText, 0, 250,'utf-8')), 'description');

$this->breadcrumbs=array(
	'Все вопросы'=>array('index'),
	CHtml::encode($model->title),
);
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
 
<div class="small">
        <?php 
        $this->widget('zii.widgets.CBreadcrumbs', array(
            'homeLink'=>CHtml::link('Вопрос юристу',"/"),
            'separator'=>' / ',
            'links'=>$this->breadcrumbs,
         ));
        ?>
    </div>

<div itemscope itemtype="http://schema.org/Question">
     
    <div id="question-hero" class="">
        
        <div class="row">
            <div class="col-sm-9">
                <div  itemprop="author" itemscope itemtype="http://schema.org/Person">
                <p>
                    <?php if($model->price!=0 && $model->payed == 1):?>
                    <span class="label label-warning"><span class='glyphicon glyphicon-ruble'></span></span>
                    <?php endif;?>

                    <small>
                    <?php if($model->publishDate):?>
                        <span class="glyphicon glyphicon-calendar"></span>&nbsp;<time itemprop="dateCreated" datetime="<?php echo $model->publishDate;?>"><?php echo CustomFuncs::niceDate($model->publishDate, false); ?></time> &nbsp;&nbsp;
                    <?php endif;?>

                    <?php if($model->categories):?>
                        <?php foreach($model->categories as $category):?>
                        <span class="glyphicon glyphicon-folder-open"></span>&nbsp;&nbsp;<?php echo CHtml::link(CHtml::encode($category->name),Yii::app()->createUrl('questionCategory/alias', $category->getUrl()));?> &nbsp;&nbsp;
                        <?php endforeach;?>
                    <?php endif;?>
                    </small>
                </p>
            </div>
            </div>
            <div class="col-sm-3">
                <div class="right-align">
                    <script src="//yastatic.net/es5-shims/0.0.2/es5-shims.min.js"></script>
                    <script src="//yastatic.net/share2/share.js"></script>
                    <div class="ya-share2" data-services="vkontakte,facebook,odnoklassniki,twitter,viber,whatsapp"></div>
                </div>      
            </div>
        </div>


        <div>
        <?php if($model->title):?>
        <h1 itemprop="name" class="center-align"><?php echo CHtml::encode($model->title); ?></h1>
        <?php endif;?>
        </div>
</div>
     
    
   <div itemprop="text" class="inside" >
        <?php echo nl2br(CHtml::encode($model->questionText));?>
    </div>
    
    <p class="vert-margin30">
        <em>
            <?php if($model->authorName):?>
                <span class="glyphicon glyphicon-user"></span>&nbsp;<span itemprop="name"><?php echo CHtml::encode($model->authorName); ?></span> &nbsp;&nbsp;
            <?php endif;?>
            <?php if($model->town):?>
                <span class="glyphicon glyphicon-map-marker"></span>&nbsp;<?php echo CHtml::link(CHtml::encode($model->town->name),Yii::app()->createUrl('town/alias',
                    array(
                        'name'          =>  $model->town->alias,
                        'countryAlias'  =>  $model->town->country->alias,
                        'regionAlias'   =>  $model->town->region->alias,    
                        ))); ?> &nbsp;
                    <?php if(!$model->town->isCapital):?>
                        <span class="text-muted">(<?php echo $model->town->region->name;?>)</span>
                    <?php endif;?>
                    &nbsp;&nbsp;
            <?php endif;?>
        </em>
    </p>
    

    
<?php if(in_array(Yii::app()->user->role, array(User::ROLE_JURIST, User::ROLE_ROOT)) && !in_array(Yii::app()->user->id, $answersAuthors)):?>

        <?php if(Yii::app()->user->isVerified || Yii::app()->user->role == User::ROLE_ROOT):?>
    
            
            <div class='flat-panel inside vert-margin30'>
			<h2 class="" >Ваш ответ на вопрос клиента:</h2>
            <?php $this->renderPartial('application.views.answer._form', array('model'=>$answerModel));?>
                
            </div>
        <?php else:?>
            <?php if(sizeof($lastRequest)):?>
                <div class="alert alert-danger">
                    <p>
                    Вы не можете отвечать на вопросы.
                    Ваша заявка на подтверждение квалификации находится на проверке модератором. 
                    Пожалуйста, дождитесь модерации.
                    </p>
                </div>
                
            <?php elseif(sizeof($lastRequest) == 0):?>
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
    <?php if($answersDataProvider->itemCount>0):?>
    <?php if($answersDataProvider->itemCount == 1):?>
            <h2>Ответ юриста на вопрос</h2>
        <?php else:?>
            <h2>Ответы юристов на вопрос</h2>
        <?php endif;?>
    <?php endif;?>
    
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
    
</div> <!-- Question --> 

<br/>

<?php if(Yii::app()->user->role != User::ROLE_JURIST):?>
    <h3 class="header-block-light-grey vert-margin20">На ваши вопросы отвечают:</h3>
    <div class='vert-margin20' >
		
        <div class="row">
            
            <?php
                // выводим виджет с топовыми юристами
                $this->widget('application.widgets.TopYurists.TopYurists', array(
                    'cacheTime' =>  0,
                ));
            ?>
            
        </div>
    </div>
<?php endif;?>

    <?php 
    // если перед этим опубликовали вопрос, отправим цель в метрику
        if(Yii::app()->user->getState('justPublished') == 1):?>
    
    <script type="text/javascript">
        window.onload = function() {
            console.log('works');
            yaCounter26550786.reachGoal('questionPublished');
        }
    </script>
    
    <?php Yii::app()->user->setState('justPublished',0);?>
    <?php endif; ?>

