<!DOCTYPE html>
<html lang="en">
<head>        
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    <title><?php echo $title?></title>
    <link rel="icon" type="image/ico" href="favicon.ico"/>
    <link href="<?php echo base_url()?>assets/css/stylesheets.css" rel="stylesheet" type="text/css" />
    <script type='text/javascript' src='http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js'></script>
    <script type='text/javascript' src='<?php echo base_url()?>assets/js/plugins/bootstrap.min.js'></script>
 </head>
<body>
    <div class="loginBox">  
         <?php $this->load->view('alerts/errors')?>
        <div class="loginHead">
            <img src="<?php echo base_url()?>assets/img/logo.png" alt="Wisi" title="Wisi"/>
        </div>
        <form class="form-horizontal"  method="POST">            
            <div class="control-group">
                <label for="email">Email</label>                
                <input type="text" id="email" name="email" value="<?php echo set_value('email'); ?>"/>
            </div>
            <div class="control-group">
                <label for="password">Clave</label>                
                <input type="password" id="password" name="password"/>                
            </div>
            <input type="hidden" name="action" value="dologin"/> 
            <div class="form-actions">
                <button type="submit" class="btn btn-block">Iniciar</button>
                <a href="<?php echo base_url('recovery')?>" class="btn btn-block">Recuperar contraseña</a>
            </div>
        </form>        
    </div>    
</body>
</html>