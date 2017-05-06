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
    Yii::app()->clientScript->registerCssFile('/js/jquery-ui/jquery-ui.css');
    Yii::app()->clientScript->registerCssFile("/css/2017/admin.css");

    Yii::app()->clientScript->registerScriptFile("/js/respond.min.js");
    Yii::app()->clientScript->registerScriptFile("jquery.js");
    Yii::app()->clientScript->registerScriptFile("/js/jquery-ui/jquery-ui.min.js");
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
            <a class="navbar-brand" href="/admin/">Панель управления проектом</a>
           
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
                             <h4>Админ панель</h4>
                        </div>
                        <div class="panel-body">
                        <ul id="left-menu">
                          
							<li><?php echo CHtml::link("<span class='glyphicon glyphicon-filter'></span>  Лиды", Yii::app()->createUrl('/admin/lead/index'));?>
							<?php echo CHtml::link('Добавить', Yii::app()->createUrl('/admin/lead/create/'), array('class'=>'btn btn-info btn-xs'));?></li>
                                <ul>
									<li><?php echo CHtml::link("<span class='glyphicon glyphicon-filter'></span>  Проданные", Yii::app()->createUrl('/admin/lead/index', array('status'=>  Lead100::LEAD_STATUS_SENT)));?></li>
                                    <li>
                                        <?php echo CHtml::link("<span class='glyphicon glyphicon-filter'></span>  На отбраковке", Yii::app()->createUrl('/admin/lead/index', array('status'=>  Lead100::LEAD_STATUS_NABRAK)));?>  
                                        <span class="label label-default"><?php echo Lead100::getStatusCounter(Lead100::LEAD_STATUS_NABRAK);?></span>
                                    </li>
                                    <li><?php echo CHtml::link("<span class='glyphicon glyphicon-filter'></span>  Брак", Yii::app()->createUrl('/admin/lead/index', array('status'=>  Lead100::LEAD_STATUS_BRAK)));?></li>
                                    <li><?php echo CHtml::link("<span class='glyphicon glyphicon-filter'></span>  Возврат", Yii::app()->createUrl('/admin/lead/index', array('status'=>  Lead100::LEAD_STATUS_RETURN)));?></li>
                                </ul>
							<li><?php echo CHtml::link("<span class='glyphicon glyphicon-briefcase'></span>  Кампании", Yii::app()->createUrl('/admin/campaign'));?></li>
                            <li><?php echo CHtml::link("<span class='glyphicon glyphicon-signal'></span>  Статистика", Yii::app()->createUrl('/admin/lead/stats'));?></li>
                            <li><?php echo CHtml::link("<span class='glyphicon glyphicon-share-alt'></span>  Источники", Yii::app()->createUrl('/admin/leadsource'));?></li>                        
                            <li><?php echo CHtml::link("<span class='glyphicon glyphicon-share-alt'></span>  Касса", Yii::app()->createUrl('/admin/money'));?></li>
                            <li><?php echo CHtml::link("<span class='glyphicon glyphicon-briefcase'></span>  Пользователи", Yii::app()->createUrl('/admin/user/index'));?></li>
                            <li><?php echo CHtml::link("<span class='glyphicon glyphicon-eye-close'></span>  Запросы на смену статуса ", Yii::app()->createUrl('/admin/userStatusRequest'));?> <span class="badge badge-default"><?php echo UserStatusRequest::getNewRequestsCount();?></span></li>
	
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
                    
                    
                    
                    <div class="panel panel-default">
						<div class="panel-heading">
                             <h4>Управление контентом</h4>
                        </div>
                        <div class="panel-body">  
						<?php if(Yii::app()->user->checkAccess(User::ROLE_EDITOR)):?>

                        <ul id="left-menu">
							
                            <li><?php echo CHtml::link("<span class='glyphicon glyphicon-question-sign'></span>  Вопросы", Yii::app()->createUrl('/admin/question'));?>
                                <?php if(Yii::app()->user->checkAccess(User::ROLE_ROOT) || Yii::app()->user->checkAccess(User::ROLE_EDITOR) || Yii::app()->user->role == User::ROLE_SECRETARY):?>
                                <ul>
									<li>
                                        <?php echo CHtml::link("<span class='glyphicon glyphicon-list'></span> " . 'VIP', Yii::app()->createUrl('/admin/question/vip'));?>
                                    </li>
                                    <li>
                                        <?php echo CHtml::link("<span class='glyphicon glyphicon-adjust'></span> " . 'Недозаполненные', Yii::app()->createUrl('/admin/question/index',array('status'=>  Question::STATUS_PRESAVE)));?>
                                    </li>
                                    <li>
                                        <?php echo CHtml::link("<span class='glyphicon glyphicon-filter'></span> " . 'Email не указан или не подтвержден', Yii::app()->createUrl('/admin/question/index',array('status'=>  Question::STATUS_NEW)));?>
                                    </li>
                                    <li>
                                        <?php echo CHtml::link("<span class='glyphicon glyphicon-check'></span> " . 'Ждет публикации', Yii::app()->createUrl('/admin/question/index',array('status'=>  Question::STATUS_MODERATED)));?>
                                    </li>
									<li>
                                        <?php echo CHtml::link("<span class='glyphicon glyphicon-filter'></span> " . 'Предв. опубликован', Yii::app()->createUrl('/admin/question/index',array('status'=>  Question::STATUS_CHECK)));?>
                                    </li>
                                    <li>
                                        <?php echo CHtml::link("<span class='glyphicon glyphicon-ok'></span> " . 'Опубликованы', Yii::app()->createUrl('/admin/question/index',array('status'=>  Question::STATUS_PUBLISHED)));?>
                                    </li>
                                    <li>
                                        <?php echo CHtml::link("<span class='glyphicon glyphicon-fire'></span> " . 'Спам', Yii::app()->createUrl('/admin/question/index',array('status'=>  Question::STATUS_SPAM)));?>
                                    </li>
                                    <li>
                                        <?php echo CHtml::link("<span class='glyphicon glyphicon-chevron-right'></span> " . 'Вопросы без категории', Yii::app()->createUrl('/admin/question/nocat')); ?>
                                    </li>
                                    
                                    <li>
                                        <?php echo CHtml::link("<span class='glyphicon glyphicon-list'></span> " . 'Категории', Yii::app()->createUrl('/admin/questionCategory'));?>
                                    </li>
                                    
                                    <li>
                                        <?php echo CHtml::link("<span class='glyphicon glyphicon-chevron-right'></span> " . 'Редактирование', Yii::app()->createUrl('/admin/question/setTitle'));?>
                                    </li>
                                    
                                </ul>    
                                <?php endif;?>    
                            </li>
							
							<hr style="margin-top: 1px; margin-bottom: 1px;">
                            
                            <li><?php echo CHtml::link("<span class='glyphicon glyphicon-question-sign'></span>  Ответы юристов", Yii::app()->createUrl('/admin/answer'));?>
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
							</li>

							<hr style="margin-top: 1px; margin-bottom: 1px;">
							
							<li>	
                                <ul id="left-menu">
                                        <li>
                                            <?php echo CHtml::link("<span class='glyphicon glyphicon-filter'></span> " . 'Новые комментарии', Yii::app()->createUrl('/admin/comment/index',array('type'=>Comment::TYPE_ANSWER, 'status'=> Comment::STATUS_NEW)));?> <span class="badge badge-default"><?php echo Comment::newCommentsCount(Comment::TYPE_ANSWER, 300);?></span>
                                        </li>
                                        <li>
                                                <?php echo CHtml::link("<span class='glyphicon glyphicon-ok'></span> " . 'Одобренные комментарии', Yii::app()->createUrl('/admin/comment/index',array('type'=>Comment::TYPE_ANSWER, 'status'=>  Comment::STATUS_CHECKED)));?>
                                        </li>
                                        <li>
                                                <?php echo CHtml::link("<span class='glyphicon glyphicon-fire'></span> " . 'Спам комментарии', Yii::app()->createUrl('/admin/comment/index',array('type'=>Comment::TYPE_ANSWER, 'status'=>  Comment::STATUS_SPAM)));?>
                                        </li>
                                </ul>
                                <?php endif;?>    
                            </li>
							
							<hr style="margin-top: 1px; margin-bottom: 1px;">
							
							<li><?php echo CHtml::link("<span class='glyphicon glyphicon-paperclip'></span>  Каталог фирм", Yii::app()->createUrl('/admin/yurCompany'));?>
                                <?php echo CHtml::link('Добавить', Yii::app()->createUrl('/admin/yurCompany/create'), array('class'=>'btn btn-info btn-xs'));?>
                                 
									<ul id="left-menu">
										<li>
											<?php echo CHtml::link("<span class='glyphicon glyphicon-filter'></span> " . 'Новые отзывы', Yii::app()->createUrl('/admin/comment/index',array('type'=>Comment::TYPE_COMPANY, 'status'=> Comment::STATUS_NEW)));?> <span class="badge badge-default"><?php echo Comment::newCommentsCount(Comment::TYPE_COMPANY, 300);?></span>
										</li>
										<li>
											<?php echo CHtml::link("<span class='glyphicon glyphicon-ok'></span> " . 'Одобренные', Yii::app()->createUrl('/admin/comment/index',array('type'=>Comment::TYPE_COMPANY, 'status'=>  Comment::STATUS_CHECKED)));?>
										</li>
										<li>
											<?php echo CHtml::link("<span class='glyphicon glyphicon-fire'></span> " . 'Спам', Yii::app()->createUrl('/admin/comment/index',array('type'=>Comment::TYPE_COMPANY, 'status'=>  Comment::STATUS_SPAM)));?>
										</li>
									</ul>
							</li>	
							
							<?php if(Yii::app()->user->checkAccess(User::ROLE_ROOT)):?>
							<hr style="margin-top: 1px; margin-bottom: 1px;">
							
							<li><?php echo CHtml::link("<span class='glyphicon glyphicon-pencil'></span>  Блог", Yii::app()->createUrl('/admin/blog'));?></li> 
							<li><?php echo CHtml::link("<span class='glyphicon glyphicon-globe'></span>  Регионы", Yii::app()->createUrl('/admin/region'));?></li> 
							<?php endif;?> 
							
                            
                        </ul>
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