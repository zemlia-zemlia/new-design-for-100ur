<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?php echo CHtml::encode($this->pageTitle); ?></title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- Bootstrap 3.3.4 -->
    <link href="/adminlte/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <!-- FontAwesome 4.3.0 -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet"
          type="text/css"/>
    <!-- Ionicons 2.0.0 -->
    <link href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet" type="text/css"/>
    <!-- Theme style -->
    <link href="/adminlte/dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css"/>
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link href="/adminlte/dist/css/skins/_all-skins.min.css" rel="stylesheet" type="text/css"/>
    <!-- iCheck -->
    <link href="/adminlte/plugins/iCheck/flat/blue.css" rel="stylesheet" type="text/css"/>
    <!-- Morris chart -->
    <link href="/adminlte/plugins/morris/morris.css" rel="stylesheet" type="text/css"/>
    <!-- jvectormap -->
    <link href="/adminlte/plugins/jvectormap/jquery-jvectormap-1.2.2.css" rel="stylesheet" type="text/css"/>
    <!-- Date Picker -->
    <link href="/adminlte/plugins/datepicker/datepicker3.css" rel="stylesheet" type="text/css"/>
    <!-- Daterange picker -->
    <link href="/adminlte/plugins/daterangepicker/daterangepicker-bs3.css" rel="stylesheet" type="text/css"/>
    <!-- bootstrap wysihtml5 - text editor -->
    <link href="/adminlte/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css" rel="stylesheet" type="text/css"/>
    <link href="/css/2017/admin.css" rel="stylesheet" type="text/css"/>


    <?php
    Yii::app()->clientScript->registerCssFile('/css/2017/jquery-ui.css');
    Yii::app()->clientScript->registerScriptFile("/js/respond.min.js");
    Yii::app()->clientScript->registerScriptFile("jquery.js");
    Yii::app()->clientScript->registerCssFile('/css/2017/jquery-ui.css');
    Yii::app()->clientScript->registerScriptFile("/js/jquery-ui.min.js");
    Yii::app()->clientScript->registerScriptFile("/bootstrap/js/bootstrap.min.js", CClientScript::POS_END);
    Yii::app()->clientScript->registerScriptFile("/js/admin/scripts.js", CClientScript::POS_END);
    Yii::app()->ClientScript->registerScriptFile('/js/jquery.maskedinput.min.js', CClientScript::POS_END);
    Yii::app()->ClientScript->registerScriptFile('/adminlte/dist/js/app.min.js', CClientScript::POS_END);
    ?>


    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->


</head>
<body class="skin-blue sidebar-mini">
<div class="wrapper">

    <?= CController::renderPartial('webroot.themes.2020.views.admin._header'); ?>

    <?= CController::renderPartial('webroot.themes.2020.views.admin._left'); ?>
    <!-- Left side column. contains the logo and sidebar -->


    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->


        <!-- Main content -->
        <section class="content">


            <?php
            foreach (Yii::app()->user->getFlashes() as $key => $message) {
                echo '<div class="alert alert-' . $key . '">' . $message . "</div>\n";
            }


            echo $content; ?>
        </section>
    </div><!-- /.content-wrapper -->
    <footer class="main-footer">
        <div class="pull-right hidden-xs">
            <b>Version</b> 2.0
        </div>
        <strong>&copy; 2014-2020 <a href="#">100 Юристов</a>.</strong>
      </footer>

    <!-- Control Sidebar -->

    <!-- Add the sidebar's background. This div must be placed
         immediately after the control sidebar -->
    <div class='control-sidebar-bg'></div>
</div><!-- ./wrapper -->
</body>
</html>