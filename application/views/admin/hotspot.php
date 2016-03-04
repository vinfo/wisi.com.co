<script type="text/javascript">
    $(document).ready(function(){
        var initData=[{"data": "name"},
                     {"data": "location"},
                     {"data": "serial"},
                     {"data": "hardware"},
                     {"data": "cdate"},
                     {"data": "action","width": "15%" }];
        collaborative.iniDatatable('hotspotdatatable',initData,'vendor/datatable/server/hotspot.php?role=<?php echo $this->session->type_id?>');
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
                        collaborative.deleteItem("hotspot",values,"successMsg","errormsg");
                    }
                 }
         });
         /*borrar solo un usuario por click*/
          $(document).delegate(".delete_item","click",function(){
                var id=this.id.split('_');
                var conf =confirm("¿Esta seguro de aplicar esta acción?");
                var values =[id[1]];
                if (conf) {
                    collaborative.deleteItem("hotspot",values,"successMsg","errormsg");
                }
        });
        
    });
</script>
<div class="content">
    <div class="breadLine">
    <ul class="breadcrumb">
      <li><a href="<?php echo base_url()?>admin">Administrador</a> <span class="divider">></span></li>
      <li class="active">HotSpot</li>
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
                        <a href="<?php echo base_url($this->uri->segment(1))?>" class="new_entry">Agregar nuevo</a>
                   
                    </h1>
                    <div class="clear"></div>
                </div>
                <div class="block-fluid"> 
                    <form method="post" enctype="multipart/form-data">
                        
                        <div class="row-form">
                            <div class="span3">Nombre:</div>
                            <div class="span9"><input type="text" id="name" name="name" placeholder="Nombre" value="<?php echo set_value('name',isset($hotspotdata)?$hotspotdata->name:''); ?>"/></div>
                            <div class="clear"></div>
                        </div> 

                        <div class="row-form">
                            <div class="span3">Dirección de red:</div>
                            <div class="span9"><input type="text" id="location" name="location" placeholder="Dirección de red" value="<?php echo set_value('location',isset($hotspotdata)?$hotspotdata->location:''); ?>"/></div>
                            <div class="clear"></div>
                        </div>                         

                        <div class="row-form">
                            <div class="span3">Lista de direcciónes MAC:</div>
                            <div class="span9"><textarea name="serial" id="serial"><?php echo set_value('serial',isset($hotspotdata)?$hotspotdata->serial:''); ?></textarea></div>
                            <div class="clear"></div>
                        </div>
                        
                        <div class="row-form">
                            <div class="span3">Cubrimiento de area:</div>
                            <div class="span9"><textarea name="area" id="area"><?php echo set_value('area',isset($hotspotdata)?$hotspotdata->area:''); ?></textarea></div>
                            <div class="clear"></div>
                        </div>  
                        <div class="row-form">
                            <div class="span3">Valor pauta por día $:</div>
                            <div class="span9"><input type="text" id="day_amount" name="day_amount" placeholder="Valor pauta por día" value="<?php echo set_value('day_amount',isset($hotspotdata)?$hotspotdata->day_amount:''); ?>"/></div>
                            <div class="clear"></div>
                        </div> 
                        <div class="row-form">
                            <div class="span3">Valor pauta por visualización $:</div>
                            <div class="span9"><input type="text" id="print_amount" name="print_amount" placeholder="Valor pauta por visualización" value="<?php echo set_value('print_amount',isset($hotspotdata)?$hotspotdata->print_amount:''); ?>"/></div>
                            <div class="clear"></div>
                        </div> 
                        <div class="row-form">
                            <div class="span3">Valor pauta click $:</div>
                            <div class="span9"><input type="text" id="click_amount" name="click_amount" placeholder="Valor pauta click" value="<?php echo set_value('click_amount',isset($hotspotdata)?$hotspotdata->click_amount:''); ?>"/></div>
                            <div class="clear"></div>
                        </div> 
                        
                        <div class="row-form">
                            <div class="span3">Dispositivos WIFI</div>
                            <div class="span7">
                                <select  id="hardware" name="hardware">
                                    <option value="">--Seleccionar--</option>
                                    <?php if(isset($devices)&&!empty($devices)): foreach($devices as $device):?>
                                    <option value="<?php echo $device->id?>" <?php echo (isset($hotspotdata)&&$hotspotdata->hardware==$device->id)?"selected":""?>><?php echo $device->name?></option>
                                    <?php endforeach;endif;?>
                                </select>
                            </div>
                            <div class="clear"></div>
                        </div>
                        <div class="row-form">
                            <div class="span3">Estado</div>
                            <div class="span7">
                                <select  id="status" name="status">
                                    <option value="">--Seleccionar--</option>
                                    <option value="1" <?php echo (isset($hotspotdata)&&$hotspotdata->status==1)?"selected":""?>>Activo</option>
                                    <option value="0" <?php echo (isset($hotspotdata)&&$hotspotdata->status==0)?"selected":""?>>Inactivo</option>
                                </select>
                            </div>
                            <div class="clear"></div>
                        </div>
                        <div class="row-form">
                            <input type="hidden" name="action" value="<?php echo isset($hotspotdata)?"edit":"new"?>"/>
                            <button type="submit" class="btn btn-primary"><?php echo isset($hotspotdata)?"Editar":"Crear"?></button>
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
                     <table id="hotspotdatatable" class="table table-responsive" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Dirección de red</th>
                                        <th>MAC</th>
                                        <th>Dispositivo WIFI</th>
                                        <th>Fecha</th>
                                        <th>Acción</th>
                                    </tr>
                                </thead>

                                <tfoot>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Dirección de red</th>
                                        <th>MAC</th>
                                        <th>Dispositivo WIFI</th>
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

