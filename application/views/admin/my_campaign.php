<script type="text/javascript">
    $(document).ready(function(){
        var initData=[{"data": "name"},
                     {"data": "views"},
                     {"data": "clicks"},
                     {"data": "quantity"},
                     {"data": "type"},
                     {"data": "start_date"},
                     {"data": "finish_date"},
                     {"data": "action","width": "15%" }];
        collaborative.iniDatatable('campaigndatatable',initData,'vendor/datatable/server/campaign.php?role=<?php echo $this->session->type_id?>&user=<?php echo $this->session->id?>&status=0');
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
                        collaborative.deleteItem("campaign",values,"successMsg","errormsg");
                    }
                 }
         });
         /*borrar solo un usuario por click*/
          $(document).delegate(".delete_item","click",function(){
                var id=this.id.split('_');
                var conf =confirm("¿Esta seguro de aplicar esta acción?");
                var values =[id[1]];
                if (conf) {
                    collaborative.deleteItem("campaign",values,"successMsg","errormsg");
                }
            });
          
      $(".campaign_status").click(function(){
         var status=this.rel;
        collaborative.iniDatatable('campaigndatatable',initData,'vendor/datatable/server/campaign.php?role=<?php echo $this->session->type_id?>&user=<?php echo $this->session->id?>&status='+status);
        }); 
    });
</script>
<div class="content">
    <div class="breadLine">
        <ul class="breadcrumb">
            <li><a href="<?php echo base_url() ?>admin">Administrador</a> <span class="divider">></span></li>
            <li class="active">Mis campañas</li>
        </ul>
    </div>
    <div class="workplace">
        <a rel="0" class="btn campaign_status" >Campañas pendientes</a>
        <a rel="1" class="btn campaign_status" >Campañas activas</a>
        <a rel="2" class="btn campaign_status" >Campañas rechazadas</a>
        <a rel="3" class="btn campaign_status" >Campañas cerradas</a>
        
        <div class="alert alert-success none"  id="successMsg"></div>
        <div class="alert alert-danger none" id="errormsg"></div>
        <div class="row-fluid">
            <div class="span12">
                <div class="head">
                    <div class="isw-archive"></div>
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
                     <table id="campaigndatatable" class="table table-responsive" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>Campaña</th>
                                        <th>Visualizaciones</th>
                                        <th>Clicks</th>
                                        <th>Cantidad por tipo cobro</th>
                                        <th>Tipo campaña</th>
                                        <th>Fecha Inicio</th>
                                        <th>Fecha Fin</th>
                                        <th>Acción</th>
                                    </tr>
                                </thead>

                                <tfoot>
                                    <tr>
                                        <th>Campaña</th>
                                        <th>Visualizaciones</th>
                                        <th>Clicks</th>
                                        <th>Cantidad por tipo cobro</th>
                                        <th>Tipo campaña</th>
                                        <th>Fecha Inicio</th>
                                        <th>Fecha Fin</th>
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

