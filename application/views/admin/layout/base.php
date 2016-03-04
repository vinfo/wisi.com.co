<!DOCTYPE html>
<html lang="en">
<head>        
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />

    <title><?php echo $title?></title>
    <link rel="icon" type="image/ico" href="favicon.ico"/>
    <link href="<?php echo base_url()?>assets/css/stylesheets.css" rel="stylesheet" type="text/css" />
    <link href='<?php echo base_url()?>assets/css/fullcalendar.print.css' rel='stylesheet' type='text/css'  media='print' />
    <link href="<?php echo base_url()?>vendor/datatable/css/tableTools.css" rel="stylesheet"/>
    
    <script type='text/javascript' src='<?php echo base_url()?>assets/js/jquery.min.js'></script>
    <script type='text/javascript' src='<?php echo base_url()?>assets/js/jquery-ui.js'></script>
    <script type='text/javascript' src='<?php echo base_url()?>assets/js/plugins/jquery/jquery.mousewheel.min.js'></script>
    <script type='text/javascript' src='<?php echo base_url()?>assets/js/plugins/cookie/jquery.cookies.2.2.0.min.js'></script>
    <script type='text/javascript' src='<?php echo base_url()?>assets/js/plugins/bootstrap.min.js'></script>
    <script type='text/javascript' src='<?php echo base_url()?>assets/js/plugins/charts/excanvas.min.js'></script>
    <script type='text/javascript' src='<?php echo base_url()?>assets/js/plugins/charts/jquery.flot.js'></script>    
    <script type='text/javascript' src='<?php echo base_url()?>assets/js/plugins/charts/jquery.flot.stack.js'></script>    
    <script type='text/javascript' src='<?php echo base_url()?>assets/js/plugins/charts/jquery.flot.pie.js'></script>
    <script type='text/javascript' src='<?php echo base_url()?>assets/js/plugins/charts/jquery.flot.resize.js'></script>
    <script type='text/javascript' src='<?php echo base_url()?>assets/js/plugins/sparklines/jquery.sparkline.min.js'></script>
    <script type='text/javascript' src='<?php echo base_url()?>assets/js/plugins/fullcalendar/fullcalendar.min.js'></script>
    <script type='text/javascript' src='<?php echo base_url()?>assets/js/plugins/select2/select2.min.js'></script>
    <script type='text/javascript' src='<?php echo base_url()?>assets/js/plugins/uniform/uniform.js'></script>
    <script type='text/javascript' src='<?php echo base_url()?>assets/js/plugins/maskedinput/jquery.maskedinput-1.3.min.js'></script>
    <script type='text/javascript' src='<?php echo base_url()?>assets/js/plugins/mcustomscrollbar/jquery.mCustomScrollbar.min.js'></script>
    <script type='text/javascript' src='<?php echo base_url()?>assets/js/plugins/animatedprogressbar/animated_progressbar.js'></script>
    <script type='text/javascript' src='<?php echo base_url()?>assets/js/plugins/qtip/jquery.qtip-1.0.0-rc3.min.js'></script>
    <script type='text/javascript' src='<?php echo base_url()?>assets/js/plugins/cleditor/jquery.cleditor.js'></script>
    <script type='text/javascript' src='<?php echo base_url()?>assets/js/plugins/dataTables/jquery.dataTables.min.js'></script>    
    <script type='text/javascript' src='<?php echo base_url()?>assets/js/plugins/fancybox/jquery.fancybox.pack.js'></script>
    <script type='text/javascript' src='<?php echo base_url()?>assets/js/cookies.js'></script>
    <script type='text/javascript' src='<?php echo base_url()?>assets/js/actions.js'></script>
    <script type='text/javascript' src='<?php echo base_url()?>assets/js/charts.js'></script>
    <script type='text/javascript' src='<?php echo base_url()?>assets/js/plugins.js'></script>
    <script type='text/javascript' src='<?php echo base_url()?>assets/js/scripts.js'></script>
 </head>
<body>
    
    <div class="header">
        <a class="logo" href="<?php echo base_url()?>admin"><img src="<?php echo base_url()?>assets/img/logo.png" alt="Wisi" title="Wisi"/></a>
        <ul class="header_menu">
            <li class="list_icon"><a href="#">&nbsp;</a></li>
        </ul>    
    </div>
    
    <?php if($this->session->id) $this->load->view('admin/layout/menu')?>
    <?php $this->load->view($body) ?>
    <script src="<?php echo base_url()?>vendor/datatable/js/jquery.dataTables.min.js"></script>
    <script src="<?php echo base_url()?>vendor/datatable/js/dataTables.tableTools.min.js"></script>
</body>
</html>