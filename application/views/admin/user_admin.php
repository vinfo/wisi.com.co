<script type="text/javascript">
    $(document).ready(function(){
        var initData=[{"data": "firstname"},
                     {"data": "lastname"},
                     {"data": "email"},
                     {"data": "type"},
                     {"data": "cdate"},
                     {"data": "action","width": "15%" }];
        collaborative.iniDatatable('userdatatable',initData,'vendor/datatable/server/user.php?role=<?php echo $this->session->type_id?>&current_type=<?php echo $realType?>');
        collaborative.selectAllCheckboxes("selectAll","selectItem");
        /*borrar ususarios seleccionados en el chkbox*/
        $("#actionChk").change(function(){
                var chk =$(".selectItem:checked").length;
                var values =[];
                if(chk>0&&this.value==1){
                    var conf =confirm("¿Esta seguro de aplicar esta acción?");
                    $(".selectItem:checked").each(function(i,v){
                        values.push(v.value);
                    });
                    if (conf) {
                        collaborative.deleteItem("user",values,"successMsg","errormsg");
                    }
                 }
         });
         /*borrar solo un usuario por click*/
          $(document).delegate(".delete_item","click",function(){
                var id=this.id.split('_');
                var conf =confirm("¿Esta seguro de aplicar esta acción?");
                var values =[id[1]];
                if (conf) {
                    collaborative.deleteItem("user",values,"successMsg","errormsg");
                }
        });
        /*obtener ciudades segun su pais*/
        $("#country").change(function(){
            var code =this.value;
            var innerHtml="";
            $.get(collaborative.base_url+"serve/getcities/?country="+code,function(res){
                
                if(res.status=="ok"){
                    $.each(res.data,function(i,v){
                        innerHtml+='<option value='+v.id+'>'+v.city+'</option>'
                    });
                }
                 $("#city").html(innerHtml);  
            });
        });
    });
</script>
<div class="content">
    <div class="breadLine">
    <ul class="breadcrumb">
      <li><a href="<?php echo base_url()?>admin">Administrador</a> <span class="divider">></span></li>
      <li class="active">Usuarios</li>
    </ul>
       
  </div>
    
    <div class="workplace">
        <?php $this->load->view('alerts/errors')?>
        <?php $this->load->view('alerts/success')?>
        
        <div class="row-fluid">
            <div class="span12">
                <div class="head">
                    <div class="isw-user"></div>
                    <h1>
                        <?php echo $title ?>
                            <a href="<?php echo base_url($this->uri->segment(1)."/".$this->uri->segment(2))?>" class="new_entry">Agregar nuevo</a>
                    </h1>
                    
                    <div class="clear"></div>
                </div>
                <div class="block-fluid"> 
                    <form method="post" enctype="multipart/form-data">
                        
                        <div class="row-form">
                            <img src="<?php  echo (isset($userdata))?GetUserImage($userdata->image,"small"):base_url("assets/media/user/default.png")?>"
                                alt="<?php echo isset($userdata)?$userdata->name:"usuario"?>"
                                title="<?php echo isset($userdata)?$userdata->name:"usuario"?>" 
                                class="img-circle img-thumbnail" width="150" height="150"/>
                        </div>

                        <div class="row-form">
                            <div class="span3">Nombres:</div>
                            <div class="span9"><input type="text" id="name" name="name" placeholder="Nombre" value="<?php echo set_value('name',isset($userdata)?$userdata->name:''); ?>"/></div>
                            <div class="clear"></div>
                        </div> 

                        <div class="row-form">
                            <div class="span3">Apellidos:</div>
                            <div class="span9"><input type="text" id="lastname" name="lastname" placeholder="Apellido" value="<?php echo set_value('lastname',isset($userdata)?$userdata->lastname:''); ?>"/></div>
                            <div class="clear"></div>
                        </div>                         

                        <div class="row-form">
                            <div class="span3">Email:</div>
                            <div class="span9"><input type="text" id="email" name="email" placeholder="Correo" value="<?php echo set_value('email',isset($userdata)?$userdata->email:''); ?>" <?php echo isset($userdata)?"readonly":""?>/></div>
                            <div class="clear"></div>
                        </div> 

                        <div class="row-form">
                            <div class="span3">Contraseña:</div>
                            <div class="span9"><input type="password" id="password" name="password" placeholder="Contraseña"/></div>
                            <div class="clear"></div>
                            <?php if(isset($userdata)&&$userdata):?>
                                <input type="hidden" name="hpass" value="<?php echo $userdata->password;?>"/>
                            <?php endif;?>
                        </div>
                        <div class="row-form">
                            <div class="span3">Confirmar contraseña:</div>
                            <div class="span9"><input type="password" id="re_password" name="re_password" placeholder="Confirma contraseña"/></div>
                            <div class="clear"></div>
                        </div>
                        <div class="row-form">
                            <div class="span3">Imgane de usuario:</div>
                            <div class="span9"><input type="file" class="form-control-file" id="image" name="image"></div>
                            <small class="text-muted">Solo se permite archivos de tipo jpg|png|gif.</small>
                            <div class="clear"></div>
                        </div> 
                        <div class="row-form">
                            <div class="span3">Tipo</div>
                            <div class="span7">
                                <select  id="type_id" name="type_id" <?php echo isset($avoidDataTable)?"disabled":""?>>
                                    <option value="">--Seleccionar--</option>
                                    <?php if(isset($roles)&&!empty($roles)): $i=1; foreach($roles as $role):?>
                                    <option value="<?php echo $role->id?>" <?php echo (isset($userdata)&&$userdata->type_id==$role->id)?"selected":""?>><?php echo $role->name?></option>
                                    <?php if($i==2)break; $i++;endforeach;endif;?>
                                </select>
                            </div>
                            <div class="clear"></div>
                        </div>
                        <div class="row-form">
                            <div class="span3">Estado</div>
                            <div class="span7">
                                <select  id="status" name="status" <?php echo isset($avoidDataTable)?"disabled":""?>>
                                    <option value="">--Seleccionar--</option>
                                    <option value="1" <?php echo (isset($userdata)&&$userdata->status==1)?"selected":""?>>Activo</option>
                                    <option value="0" <?php echo (isset($userdata)&&$userdata->status==0)?"selected":""?>>Inactivo</option>
                                </select>
                            </div>
                            <?php if(isset($avoidDataTable)):?>
                            <input type="hidden" name="status" value="<?php echo $userdata->status?>"/>
                            <?php endif;?>
                            <div class="clear"></div>
                        </div>
                        <div class="row-form">
                            <input type="hidden" name="action" value="<?php echo isset($userdata)?"edit":"new"?>"/>
                            <button type="submit" class="btn btn-primary"><?php echo isset($userdata)?"Editar":"Crear"?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
       
        <!--Data table-->
        <div class="alert alert-success none"  id="successMsg"></div>
        <div class="alert alert-danger none" id="errormsg"></div>
        <div class="row-fluid">
            <div class="span12">
                <div class="head">
                    <div class="isw-user"></div>
                    <h1><?php echo $title ?></h1>
                    <div class="clear"></div>
                </div>
                <div class="block-fluid table-sorting">     
                    <div class="datatable_action">
                            <select name="actionChk" id="actionChk">
                                <option value="">Acción</option>
                                <option value="1">Eliminar</option>
                            </select>
                            <label class="dinline">
                                Seleccionar todos
                                <input type="checkbox" name="selectAll" id="selectAll" value="1" />
                            </label>
                        </div>
                     <table id="userdatatable" class="table table-responsive" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>Nombres</th>
                                        <th>Apellidos</th>
                                        <th>Correo</th>
                                        <th>Tipo</th>
                                        <th>Fecha</th>
                                        <th>Acción</th>
                                    </tr>
                                </thead>

                                <tfoot>
                                    <tr>
                                        <th>Nombres</th>
                                        <th>Apellidos</th>
                                        <th>Correo</th>
                                        <th>Tipo</th>
                                        <th>Fecha</th>
                                        <th>Acción</th>
                                    </tr>
                                </tfoot>
                     </table>                
                    <div class="clear"></div>
                </div>
            </div>
        </div>
    </div>
</div>