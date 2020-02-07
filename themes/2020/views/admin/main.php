<?php

?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title><?= YiiBase::getPathOfAlias('web.themes.2020.views.admin')?></title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- Bootstrap 3.3.4 -->
    <link href="/adminlte/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- FontAwesome 4.3.0 -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <!-- Ionicons 2.0.0 -->
    <link href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet" type="text/css" />    
    <!-- Theme style -->
    <link href="/adminlte/dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link href="/adminlte/dist/css/skins/_all-skins.min.css" rel="stylesheet" type="text/css" />
    <!-- iCheck -->
    <link href="/adminlte/plugins/iCheck/flat/blue.css" rel="stylesheet" type="text/css" />
    <!-- Morris chart -->
    <link href="/adminlte/plugins/morris/morris.css" rel="stylesheet" type="text/css" />
    <!-- jvectormap -->
    <link href="/adminlte/plugins/jvectormap/jquery-jvectormap-1.2.2.css" rel="stylesheet" type="text/css" />
    <!-- Date Picker -->
    <link href="/adminlte/plugins/datepicker/datepicker3.css" rel="stylesheet" type="text/css" />
    <!-- Daterange picker -->
    <link href="/adminlte/plugins/daterangepicker/daterangepicker-bs3.css" rel="stylesheet" type="text/css" />
    <!-- bootstrap wysihtml5 - text editor -->
    <link href="/adminlte/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css" rel="stylesheet" type="text/css" />



      <?php
      Yii::app()->clientScript->registerScriptFile("/js/respond.min.js");
      Yii::app()->clientScript->registerScriptFile("jquery.js");
      Yii::app()->clientScript->registerScriptFile("/js/jquery-ui/jquery-ui.min.js");
      Yii::app()->clientScript->registerScriptFile("/bootstrap/js/bootstrap.min.js", CClientScript::POS_END);
      Yii::app()->clientScript->registerScriptFile("/js/admin/scripts.js", CClientScript::POS_END);
      Yii::app()->ClientScript->registerScriptFile('/js/jquery.maskedinput.min.js', CClientScript::POS_END);
      Yii::app()->ClientScript->registerScriptFile('/adminlte/dist/js/app.min.js', CClientScript::POS_END);
      ?>

      <?php
      Yii::app()->clientScript->registerCssFile("/css/2017/admin.css");
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
              <?php echo $content; ?>
          </section>
      </div><!-- /.content-wrapper -->
      <footer class="main-footer">
        <div class="pull-right hidden-xs">
          <b>Version</b> 2.0
        </div>
        <strong>Copyright &copy; 2014-2020 <a href="http://almsaeedstudio.com">100 Юристов</a>.</strong> All rights reserved.
      </footer>
      
      <!-- Control Sidebar -->      

      <!-- Add the sidebar's background. This div must be placed
           immediately after the control sidebar -->
      <div class='control-sidebar-bg'></div>
    </div><!-- ./wrapper -->

    <!-- jQuery 2.1.4 -->
<!--    <script src="/adminlte/plugins/jQuery/jQuery-2.1.4.min.js"></script>-->
<!--    <!-- jQuery UI 1.11.2 -->
<!--    <script src="http://code.jquery.com/ui/1.11.2/jquery-ui.min.js" type="text/javascript"></script>-->
<!--    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<!--    <script>-->
<!--      $.widget.bridge('uibutton', $.ui.button);-->
<!--    </script>-->
<!--    <!-- Bootstrap 3.3.2 JS -->
<!--    <script src="/adminlte/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>-->
<!--    <!-- Morris.js charts -->
<!--    <script src="http://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>-->
<!--    <script src="/adminlte/plugins/morris/morris.min.js" type="text/javascript"></script>-->
<!--    <!-- Sparkline -->
<!--    <script src="/adminlte/plugins/sparkline/jquery.sparkline.min.js" type="text/javascript"></script>-->
<!--    <!-- jvectormap -->
<!--    <script src="/adminlte/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js" type="text/javascript"></script>-->
<!--    <script src="/adminlte/plugins/jvectormap/jquery-jvectormap-world-mill-en.js" type="text/javascript"></script>-->
<!--    <!-- jQuery Knob Chart -->
<!--    <script src="/adminlte/plugins/knob/jquery.knob.js" type="text/javascript"></script>-->
<!--    <!-- daterangepicker -->
<!--    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js" type="text/javascript"></script>-->
<!--    <script src="/adminlte/plugins/daterangepicker/daterangepicker.js" type="text/javascript"></script>-->
<!--    <!-- datepicker -->
<!--    <script src="/adminlte/plugins/datepicker/bootstrap-datepicker.js" type="text/javascript"></script>-->
<!--    <!-- Bootstrap WYSIHTML5 -->
<!--    <script src="/adminlte/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js" type="text/javascript"></script>-->
<!--    <!-- Slimscroll -->
<!--    <script src="/adminlte/plugins/slimScroll/jquery.slimscroll.min.js" type="text/javascript"></script>-->
<!--    <!-- FastClick -->
<!--    <script src='/adminlte/plugins/fastclick/fastclick.min.js'></script>-->
<!--    <!-- AdminLTE App -->
<!--    <script src="/adminlte/dist/js/app.min.js" type="text/javascript"></script>-->
<!--    -->
<!--    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<!--    <script src="/adminlte/dist/js/pages/dashboard.js" type="text/javascript"></script>-->
<!--    -->
<!--    <!-- AdminLTE for demo purposes -->
<!--    <script src="/adminlte/dist/js/demo.js" type="text/javascript"></script>-->
  </body>
</html>