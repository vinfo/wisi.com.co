<script type="text/javascript">
    $(document).ready(function(){
        $('select.readonly').prop('disabled', true);
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
        <?php if(isset($userdata)=="edit"){?>
        <div class="row-fluid">
            <div class="span12">
                <div class="head">
                    <div class="isw-user"></div>
                    <h1>
                        <?php echo $title ?>
                    </h1>
                    
                    <div class="clear"></div>
                </div>
                <div class="block-fluid">                    
                    <form method="post" enctype="multipart/form-data">                        
                        <div class="row-form">
                            <img src="<?php  echo (isset($userdata))?GetUserImage($userdata->image,"small"):base_url("assets/media/user/default_logo.png")?>"
                                alt="<?php echo isset($userdata)?$userdata->name:"usuario"?>"
                                title="<?php echo isset($userdata)?$userdata->name:"usuario"?>" 
                                class="img-circle img-thumbnail" width="150" height="150"/>
                        </div>

                        <div class="row-form">
                            <div class="span3">Nombres:</div>
                            <div class="span9"><input type="text" id="name" name="name" placeholder="Nombre" value="<?php echo set_value('name',isset($userdata)?$userdata->name:''); ?>"<?php echo $readonly;?>/></div>
                            <div class="clear"></div>
                        </div> 

                        <div class="row-form">
                            <div class="span3">Apellidos:</div>
                            <div class="span9"><input type="text" id="lastname" name="lastname" placeholder="Apellido" value="<?php echo set_value('lastname',isset($userdata)?$userdata->lastname:''); ?>" <?php echo $readonly;?>/></div>
                            <div class="clear"></div>
                        </div>                         

                        <div class="row-form">
                            <div class="span3">Email:</div>
                            <div class="span9"><input type="text" id="email" name="email" placeholder="Correo" value="<?php echo set_value('email',isset($userdata)?$userdata->email:''); ?>" <?php echo isset($userdata)?"readonly":""?>/></div>
                            <div class="clear"></div>
                        </div> 

                        <div class="row-form">
                            <div class="span3">Contraseña:</div>
                            <div class="span9"><input type="password" id="password" name="password" placeholder="Contraseña" <?php echo $readonly;?>/></div>
                            <div class="clear"></div>
                            <?php if(isset($userdata)&&$userdata):?>
                                <input type="hidden" name="hpass" value="<?php echo $userdata->password;?>"/>
                            <?php endif;?>
                        </div>
                        <div class="row-form">
                            <div class="span3">Confirmar contraseña:</div>
                            <div class="span9"><input type="password" id="re_password" name="re_password" placeholder="Confirma contraseña" <?php echo $readonly;?>/></div>
                            <div class="clear"></div>
                        </div>                         
                        <div class="row-form">
                            <div class="span3">Dirección:</div>
                            <div class="span9"><input type="text" id="phone" name="address" placeholder="Dirección" value="<?php echo set_value('address',isset($userdata)?$userdata->address:''); ?>" <?php echo $readonly;?>/></div>
                            <div class="clear"></div>
                        </div> 
                        <div class="row-form">
                            <div class="span3">Teléfono:</div>
                            <div class="span9"><input type="text" id="phone" name="phone" placeholder="Teléfono" value="<?php echo set_value('phone',isset($userdata)?$userdata->phone:''); ?>" <?php echo $readonly;?>/></div>
                            <div class="clear"></div>
                        </div> 
                        <div class="row-form">
                            <div class="span3">Celular:</div>
                            <div class="span9"><input type="text" id="celphone" name="celphone" placeholder="Celular" value="<?php echo set_value('celphone',isset($userdata)?$userdata->celphone:''); ?>" <?php echo $readonly;?>/></div>
                            <div class="clear"></div>
                        </div> 
                        <div class="row-form">
                            <div class="span3">Pais</div>
                            <div class="span7">
                                <select  id="country" name="country" class="readonly">
                                    <option value="">--Seleccionar--</option>
                                    <?php if(isset($countries)&&!empty($countries)): foreach($countries as $country):?>
                                    <option value="<?php echo $country->code?>" <?php echo (isset($userdata)&&$userdata->country==$country->code)?"selected":""?>><?php echo $country->country?></option>
                                    <?php endforeach;endif;?>
                                </select>
                            </div>
                            <div class="clear"></div>
                        </div>
                        <div class="row-form">
                            <div class="span3">Ciudad</div>
                            <div class="span7">
                                <select  id="city" name="city"  class="readonly">
                                    <option value="">--Seleccionar--</option>
                                    <?php if(isset($cities)&&!empty($cities)): foreach($cities as $city):?>
                                    <option value="<?php echo $city->id?>" <?php echo (isset($userdata)&&$userdata->city==$city->id)?"selected":""?>><?php echo $city->city?></option>
                                    <?php endforeach;endif;?>
                                </select>
                            </div>
                            <div class="clear"></div>
                        </div>
                        
                       <div class="row-form">
                            <div class="span3">Empresa:</div>
                            <div class="span9"> <input  type="text" id="company" name="company" value="<?php echo set_value('company',isset($userdata)?  $userdata->company:''); ?>" <?php echo $readonly;?>></div>
                            <div class="clear"></div>
                        </div> 
                        <div class="row-form">
                            <div class="span3">Nit:</div>
                            <div class="span9"> <input  type="text" id="nit" name="nit" value="<?php echo set_value('nit',isset($userdata)?  $userdata->nit:''); ?>" <?php echo $readonly;?>></div>
                            <div class="clear"></div>
                        </div> 
                        
                        <div class="row-form">
                            <div class="span3">Logo:</div>
                            <div class="span9"><input type="file" class="form-control-file" id="image" name="image" <?php echo $disabled;?>></div>
                            <small class="text-muted">Solo se permite archivos de tipo jpg|png|gif.</small>
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
                            <input type="hidden" id="type_id" name="type_id" value="4"/>
                            <input type="hidden" name="action" value="<?php echo isset($userdata)?"edit":"new"?>"/>
                            <button type="submit" class="btn btn-primary"><?php echo isset($userdata)?"Editar":"Crear"?></button>
                        </div>
                    </form>                    
                </div>
            </div>
        </div>
        <?php }?>
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

