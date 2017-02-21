<!doctype html>
<html lang="ru">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta http-equiv="content-language" content="ru" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo CHtml::encode($this->pageTitle); ?></title>
<?php 
    Yii::app()->clientScript->registerCssFile("/bootstrap/css/bootstrap.min.css");
    Yii::app()->clientScript->registerCssFile('/css/2015/jquery-ui.css');
    Yii::app()->clientScript->registerCssFile("/css/2015/admin.css");

    Yii::app()->clientScript->registerScriptFile("/js/respond.min.js");
    Yii::app()->clientScript->registerScriptFile("jquery.js");
    Yii::app()->clientScript->registerScriptFile("/js/jquery-ui.min.js");
    Yii::app()->clientScript->registerScriptFile("/bootstrap/js/bootstrap.min.js", CClientScript::POS_END);
    Yii::app()->clientScript->registerScriptFile("/js/admin/scripts.js", CClientScript::POS_END);
    Yii::app()->ClientScript->registerScriptFile('/js/jquery.maskedinput.min.js', CClientScript::POS_END);

?>

</head>  

<body>
<div id="main-wrapper">    
    <nav class="navbar navbar-default" role="navigation">
        <div class="container-fluid">
          <!-- Brand and toggle get grouped for better mobile display -->
          <div class="navbar-header">
            <a class="navbar-brand" href="http://www.100yuristov.com/admin/"><b>100 Юристов</b> - Панель управления</a>
           
          </div>
          
          
          <p class="navbar-text navbar-right">

              <?php echo CHtml::ajaxLink("<span class='glyphicon glyphicon-refresh'></span>", Yii::app()->createUrl('site/clearCache'), array(
                            'success'=>'function(data, textStatus, jqXHR)
                                {
                                    if(data==1) message = "Кэш очищен";
                                    else message = "Не удалось очистить кэш";
                                    alert(message);
                                }'    
                            ), array('title'    =>  'Очистить кеш страницы'));?>
              &nbsp;
              <span class="glyphicon glyphicon-user"></span> <?php echo CHtml::link(CHtml::encode(Yii::app()->user->name) . " " . CHtml::encode(Yii::app()->user->name2) . " " . CHtml::encode(Yii::app()->user->lastName),Yii::app()->createUrl('user')); ?> 
              <?php echo Yii::app()->user->roleName;?> &nbsp;
              <span class="glyphicon glyphicon-log-out"></span> <?php echo CHtml::link('Выход', Yii::app()->createUrl('site/logout'));?>
          </p>

        </div>
    </nav>
    
    <div id="middle">
        <div class="container-fluid">
            <div class="row">
                <?php if(!Yii::app()->user->isGuest):?>
                <div class="col-md-3 col-sm-3">
                                       
                    <?php if(Yii::app()->user->checkAccess(User::ROLE_ROOT)):?>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                             <h4>Меню админа</h4>
                        </div>
                        <div class="panel-body">
                        <ul id="left-menu">
                            <li><?php echo CHtml::link("<span class='glyphicon glyphicon-briefcase'></span>  Пользователи", Yii::app()->createUrl('/admin/user/index'));?></li>
                            <li><?php echo CHtml::link("<span class='glyphicon glyphicon-eye-close'></span>  Запросы", Yii::app()->createUrl('/admin/user/requests'));?></li>
                            <li><?php echo CHtml::link("<span class='glyphicon glyphicon-filter'></span>  Лиды", Yii::app()->createUrl('/admin/lead/index'));?></li>
                        </ul>
                        </div>
                    </div>
                    <?php endif;?>
                    
                    <?php if(Yii::app()->user->role == User::ROLE_SECRETARY):?>
                    
                    <div class="panel panel-default">
                        <div class="panel-heading">
                             <h4>Добавить новый лид</h4>
                        </div>
                        <div class="panel-body">
                            <?php
                                // выводим виджет с формой добавления нового лида типа - входящий звонок
                                $this->widget('application.widgets.CreateLead.CreateLead', array(
                                ));
                            ?>
                        </div>
                    </div>
					
					<div class="panel panel-default">
                        <div class="panel-heading">
                             <h4>Меню секретаря</h4>
                        </div>
                        <div class="panel-body">
                        <ul id="left-menu">
                            <li><?php echo CHtml::link("<span class='glyphicon glyphicon-filter'></span>  Лиды", Yii::app()->createUrl('/admin/lead/index'));?></li>
                        </ul>
                        </div>
                    </div>
					
                    <?php endif;?>
                    
                    
                    <?php if(Yii::app()->user->checkAccess(User::ROLE_ROOT)):?>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                             <h4>Продажи</h4>
                        </div>
                        <div class="panel-body">
                        <ul id="left-menu">
                            <li><?php echo CHtml::link("<span class='glyphicon glyphicon-briefcase'></span>  Кампании", Yii::app()->createUrl('/admin/campaign'));?></li>
                            <li><?php echo CHtml::link("<span class='glyphicon glyphicon-filter'></span>  Проданные", Yii::app()->createUrl('/admin/lead/index', array('status'=>  Lead::LEAD_STATUS_SENT)));?></li>
                            
                                <ul>
                                    <li>
                                        <?php echo CHtml::link("<span class='glyphicon glyphicon-filter'></span>  На отбраковке", Yii::app()->createUrl('/admin/lead/index', array('status'=>  Lead::LEAD_STATUS_NABRAK)));?>  
                                        <span class="label label-default"><?php echo Lead100::getStatusCounter(Lead100::LEAD_STATUS_NABRAK);?></span>
                                    </li>
                                    <li><?php echo CHtml::link("<span class='glyphicon glyphicon-filter'></span>  Брак", Yii::app()->createUrl('/admin/lead/index', array('status'=>  Lead100::LEAD_STATUS_BRAK)));?></li>
                                    <li><?php echo CHtml::link("<span class='glyphicon glyphicon-filter'></span>  Возврат", Yii::app()->createUrl('/admin/lead/index', array('status'=>  Lead100::LEAD_STATUS_RETURN)));?></li>
                                </ul>
                           
                            <li><?php echo CHtml::link("<span class='glyphicon glyphicon-signal'></span>  Статистика", Yii::app()->createUrl('/admin/lead/stats'));?></li>
                                                    
                        </ul>
                        </div>
                    </div>
                    <?php endif;?>
                    
                    
                    <?php if(Yii::app()->user->checkAccess(User::ROLE_EDITOR)):?>
                    <div class="panel panel-default">
                        <div class="panel-heading"><h4>Управление вопросами</h4></div>
                        <div class="panel-body">
                        <ul id="left-menu">
                            <li><?php echo CHtml::link("<span class='glyphicon glyphicon-question-sign'></span>  Вопросы", Yii::app()->createUrl('/admin/question'));?>
                                <?php if(Yii::app()->user->checkAccess(User::ROLE_ROOT) || Yii::app()->user->checkAccess(User::ROLE_EDITOR) || Yii::app()->user->role == User::ROLE_SECRETARY):?>
                                <ul>
                                    <li>
                                        <?php echo CHtml::link("<span class='glyphicon glyphicon-filter'></span> " . 'Не подтверждены', Yii::app()->createUrl('/admin/question/index',array('status'=>  Question::STATUS_NEW)));?>
                                    </li>
                                    <li>
                                        <?php echo CHtml::link("<span class='glyphicon glyphicon-filter'></span> " . 'На проверке', Yii::app()->createUrl('/admin/question/index',array('status'=>  Question::STATUS_CHECK)));?>
                                    </li>
                                    <li>
                                        <?php echo CHtml::link("<span class='glyphicon glyphicon-check'></span> " . 'Одобрены', Yii::app()->createUrl('/admin/question/index',array('status'=>  Question::STATUS_MODERATED)));?>
                                    </li>
                                    <li>
                                        <?php echo CHtml::link("<span class='glyphicon glyphicon-ok'></span> " . 'Опубликованы', Yii::app()->createUrl('/admin/question/index',array('status'=>  Question::STATUS_PUBLISHED)));?>
                                    </li>
                                    <li>
                                        <?php echo CHtml::link("<span class='glyphicon glyphicon-fire'></span> " . 'Спам', Yii::app()->createUrl('/admin/question/index',array('status'=>  Question::STATUS_SPAM)));?>
                                    </li>
                                    <li>
                                        <?php echo CHtml::link("<span class='glyphicon glyphicon-chevron-right'></span> " . 'Без кат.', Yii::app()->createUrl('/admin/question/nocat')); ?>
                                    </li>
                                    
                                    <li>
                                        <?php echo CHtml::link("<span class='glyphicon glyphicon-list'></span> " . 'Категории', Yii::app()->createUrl('/admin/questionCategory'));?>
                                    </li>
                                    
                                    <?php if(Yii::app()->user->role == User::ROLE_EDITOR):?>
                                    <li>
                                        <?php echo CHtml::link("<span class='glyphicon glyphicon-chevron-right'></span> " . 'Одобренные мной', Yii::app()->createUrl('/admin/question/byPublisher',array('id'=>Yii::app()->user->id)));?>
                                    </li>
                                    <?php endif;?>   
                                </ul>    
                                <?php endif;?>    
                            </li>
                            
                            <li><?php echo CHtml::link("<span class='glyphicon glyphicon-question-sign'></span>  Ответы", Yii::app()->createUrl('/admin/answer'));?>
                                <?php if(Yii::app()->user->checkAccess(User::ROLE_ROOT) || Yii::app()->user->checkAccess(User::ROLE_EDITOR) || Yii::app()->user->role == User::ROLE_SECRETARY):?>
                                <ul>
                                    <li>
                                        <?php echo CHtml::link("<span class='glyphicon glyphicon-filter'></span> " . 'Предв. опубликованные', Yii::app()->createUrl('/admin/answer/index',array('status'=> Answer::STATUS_NEW)));?>
                                    </li>
                                    <li>
                                        <?php echo CHtml::link("<span class='glyphicon glyphicon-ok'></span> " . 'Опубликованные', Yii::app()->createUrl('/admin/answer/index',array('status'=>  Answer::STATUS_PUBLISHED)));?>
                                    </li>
                                    <li>
                                        <?php echo CHtml::link("<span class='glyphicon glyphicon-fire'></span> " . 'Спам', Yii::app()->createUrl('/admin/answer/index',array('status'=>  Answer::STATUS_SPAM)));?>
                                    </li>
                                </ul>    
                                <?php endif;?>    
                            </li>
                            
                        </ul>
                        </div>
                    </div>
                    <?php endif;?>
                    
                    
                    <?php if(Yii::app()->user->checkAccess(User::ROLE_EDITOR)):?>
                    <div class="panel panel-default">
                        <div class="panel-heading"><h4>Управление отзывами</h4></div>
                        <div class="panel-body">
                            <ul id="left-menu">
                                <li>
                                    <?php echo CHtml::link("<span class='glyphicon glyphicon-filter'></span> " . 'Новые', Yii::app()->createUrl('/admin/comment/index',array('status'=> Comment::STATUS_NEW)));?>
                                </li>
                                <li>
                                    <?php echo CHtml::link("<span class='glyphicon glyphicon-ok'></span> " . 'Опубликованные', Yii::app()->createUrl('/admin/comment/index',array('status'=>  Comment::STATUS_CHECKED)));?>
                                </li>
                                <li>
                                    <?php echo CHtml::link("<span class='glyphicon glyphicon-fire'></span> " . 'Спам', Yii::app()->createUrl('/admin/comment/index',array('status'=>  Comment::STATUS_SPAM)));?>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <?php endif;?>
                    
                    <?php if(Yii::app()->user->checkAccess(User::ROLE_EDITOR)):?>
                    <div class="panel panel-default">
                        <div class="panel-heading"><h4>Управление контентом</h4></div>
                        <div class="panel-body">
                            <ul id="left-menu">
                                <?php if(Yii::app()->user->checkAccess(User::ROLE_ROOT)):?>
                                <li><?php echo CHtml::link("<span class='glyphicon glyphicon-pencil'></span>  Блог", Yii::app()->createUrl('/admin/blog'));?></li> 
                                <li><?php echo CHtml::link("<span class='glyphicon glyphicon-globe'></span>  Города", Yii::app()->createUrl('/admin/town'));?></li> 
                                <?php endif;?> 
                                <li><?php echo CHtml::link("<span class='glyphicon glyphicon-paperclip'></span>  Юр. компании", Yii::app()->createUrl('/admin/yurCompany'));?>
                                <?php echo CHtml::link('+', Yii::app()->createUrl('/admin/yurCompany/create'), array('class'=>'btn btn-info btn-xs'));?>
                                </li> 
                            </ul>
                        </div>
                    </div>
                    <?php endif;?>
                    
                    <?php if(Yii::app()->user->role == User::ROLE_JURIST || Yii::app()->user->role == User::ROLE_OPERATOR):?>
                    <div class="panel panel-default">
                        <div class="panel-heading"><h4>Вопрос юристу</h4></div>
                        <div class="panel-body">
                            <?php
                                // выводим виджет с произвольным опубликованным вопросом
                                $this->widget('application.widgets.RandomQuestion.RandomQuestion', array(
                                ));
                            ?>
                        </div>
                    </div>
                    <?php endif;?>
                    
                </div>
                <?php endif;?>
                <div class="col-md-9 col-sm-9">
                    <?php echo $content; ?>
                </div>
            </div>
        </div>
    </div>
    
</div> <!-- #main-wrapper-->

<?php if($_SERVER['REMOTE_ADDR']!='127.0.0.1'):?>

<?php endif;?>

</body>
</html>