<div class="menu">
  <div class="breadLine">
    <div class="arrow"></div>
    <div class="adminControl active"> Mís Datos </div>
  </div>
  <div class="admin">
  <?php /*?>
      <div class="image"> 
          <img src="<?php echo(isset($this->session))?GetUserImage($this->session->image,"small"):base_url("assets/media/user/default.png")?>" 
               class="img-polaroid" 
               title="<?php echo $this->session->name?>" 
               alt="<?php echo $this->session->name?>"
               width="50"/> 
      </div>
      <?php */?>
    <ul class="control">
      <li><span class="icon-cog"></span> <a href="<?php echo base_url("user/profile")?>">Perfil</a></li>
      <li><span class="icon-share-alt"></span> <a href="<?php echo base_url()?>logout" class="logout">Cerrar sesión</a></li>
    </ul>
    <div class="info"> <span>Bienvenido! Última visita: <?php echo $this->session->last_seen?></span> </div>
  </div>
  <ul class="navigation">
    <li class="active"> <a href="#"> <span class="isw-grid"></span><span class="text">Panel</span> </a> </li>
    <?php if($this->session->type_id <=2):?> 
    <li class="openable"> <a href="#"> <span class="isw-list"></span><span class="text">Configuración</span> </a>
      <ul>
         
        <li> <a href="<?php echo base_url('user/admin')?>"> <span class="icon-user"></span><span class="text">Administradores</span> </a> </li>
        <li> <a href="<?php echo base_url('user/advertiser')?>"> <span class="icon-user"></span><span class="text">Anunciantes</span> </a> </li>
        <li> <a href="<?php echo base_url('user/client')?>"> <span class="icon-user"></span><span class="text">Clientes</span> </a> </li>
        <li> <a href="<?php echo base_url('admin/message')?>"> <span class="icon-comment"></span><span class="text">Mensajes del sistema</span></a></li>
        
      </ul>
    </li>
    <li> <a href="<?php echo base_url('hotspot')?>"> <span class="isw-target"></span><span class="text">HotSpots</span> </a> </li> 
    <?php endif;?>
    <li class="openable"> <a href="#"> <span class="isw-archive"></span><span class="text">Campañas</span> </a> 
        <ul>
            <?php if($this->session->type_id <=2):?> 
            <li><a href="<?php echo base_url('campaign/global')?>"><span class="icon-list-alt"></span><span class="text">Campaña global</span></a></li>
            <?php else:?>
            <li><a href="<?php echo base_url('campaign/my-campaigns')?>"><span class="icon-list-alt"></span><span class="text">Mis campañas</span></a></li>
            <?php endif;?>
            <li><a href="<?php echo base_url('campaign/connection')?>"><span class="icon-list-alt"></span><span class="text">Campaña conexión</span></a></li>
            <li><a href="<?php echo base_url('campaign/action')?>"><span class="icon-list-alt"></span><span class="text">Campaña con acción</span></a></li>
        </ul>
    </li>    
    <li> <a href="#"> <span class="isw-graph"></span><span class="text">Estadísticas</span> </a> </li>    
  </ul>
  <div class="dr"><span></span></div>
</div>

