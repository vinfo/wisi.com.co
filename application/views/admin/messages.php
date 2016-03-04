<script type="text/javascript">
    $(document).ready(function(){
        var initData=[{"data": "message"},
        {"data": "type"},
        {"data": "mtime"},
        {"data": "action","width": "15%" }];
        collaborative.iniDatatable('messagesdatatable',initData,'vendor/datatable/server/messages.php?role=<?php echo $this->session->type_id?>');
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
                    collaborative.deleteItem("message",values,"successMsg","errormsg");
                }
            }
        });
        /*borrar solo un usuario por click*/
        $(document).delegate(".delete_item","click",function(){
            var id=this.id.split('_');
            var conf =confirm("¿Esta seguro de aplicar esta acción?");
            var values =[id[1]];
            if (conf) {
                collaborative.deleteItem("message",values,"successMsg","errormsg");
            }
        });
        
    });
</script>
<div class="content">
    <div class="breadLine">
        <ul class="breadcrumb">
          <li><a href="<?php echo base_url()?>admin">Administrador</a> <span class="divider">></span></li>
          <li class="active">Mensajes del sistema</li>
      </ul>
  </div>
  <div class="workplace">
    <?php $this->load->view('alerts/errors')?>
    <?php $this->load->view('alerts/success')?>

    <div class="row-fluid">
        <div class="span12">
            <div class="head">
                <div class="isw-chat"></div>
                <h1>
                    <?php echo $title ?>
                    <a href="<?php echo base_url($this->uri->segment(1)."/".$this->uri->segment(2))?>" class="new_entry">Agregar nuevo</a>

                </h1>
                <div class="clear"></div>
            </div>
            <div class="block-fluid"> 
                <form method="post" enctype="multipart/form-data">
                 <div class="row-form">
                     <div class="span3">Tiempo notificación (segundos):</div>
                     <div class="span2"> <input type="text" id="mtime" name="mtime" value="<?php echo set_value('mtime',isset($messagedata)?$messagedata->mtime:''); ?>"></div>
                     <div class="clear"></div>
                 </div> 

                 <div class="row-form">
                    <div class="span3">Mensaje:</div>
                    <div class="span9"><textarea name="message" id="message"><?php echo set_value('message',isset($messagedata)?$messagedata->message:''); ?></textarea></div>
                    <div class="clear"></div>
                </div>
                <div class="row-form">
                 <div class="span3">Imagen mensaje:</div>
                 <div class="span9"><input type="file" class="form-control-file" id="image" name="image"></div>
                 <small class="text-muted">Solo se permite archivos de tipo jpg|png|gif.</small>
                 <div class="clear"></div>
             </div>
             <div class="row-form">
                <div class="span3">Tipo</div>
                <div class="span7">
                    <select  id="type_id" name="type_id">
                        <option value="">--Seleccionar--</option>
                        <?php if(isset($types)&&!empty($types)): foreach($types as $type):?>
                            <option value="<?php echo $type->id?>" <?php echo (isset($messagedata)&&$messagedata->type_id==$type->id)?"selected":""?>><?php echo $type->name?></option>
                        <?php endforeach;endif;?>
                    </select>
                </div>
                <div class="clear"></div>
            </div>

            <div class="row-form">
                <input type="hidden" name="action" value="<?php echo isset($messagedata)?"edit":"new"?>"/>
                <button type="submit" class="btn btn-primary"><?php echo isset($messagedata)?"Editar":"Crear"?></button>
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
            <div class="isw-chat"></div>
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
            <table id="messagesdatatable" class="table table-responsive" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>Mensaje</th>
                        <th>Tipo</th>
                        <th>Tiempo</th>
                        <th>Acción</th>
                    </tr>
                </thead>

                <tfoot>
                    <tr>
                        <th>Mensaje</th>
                        <th>Tipo</th>
                        <th>Tiempo</th>
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