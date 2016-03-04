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
        collaborative.iniDatatable('campaigndatatable',initData,'vendor/datatable/server/campaign.php?role=<?php echo $this->session->type_id?>&user=<?php echo $this->session->id?>');
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
        $( ".type_cap" ).on( "click", function() {
            var type=this.id;
            $("#campign_form").show();
            $("#uniform-image").show();
            $(".checker").show();
            
            $("#cap_type").find('[value="2"]').hide().remove();
            if(type==34){
                $("#cap_media").hide();
                $("#cap_survey").show();
                $("#cap_type").append('<option value="2">Clicks</option>');
                $(".encuesta").show();
                $("#ltipo").html(" - Encuesta");
            }else if(type==33){
                $("#cap_type").append('<option value="2">Clicks</option>');
                $("#ltipo").html(" - Descargar APP");
                $(".encuesta").hide();
            }else if(type==32){
                $(".no_video").hide();                
                $("#ltipo").html(" - Video");
                $(".encuesta").hide();
            }else{
                $("#cap_survey").hide();
                $("#cap_media").show();
                $("#cap_type").append('<option value="2">Clicks</option>');
                $("#ltipo").html(" - Sitio Web");
                $(".encuesta").hide();
            }
            $("#campaing_type").val(type);
        });
        
        /*add more answers*/
        $(".answer").keydown(function(e){
           if (e.which == 13) {
               if (this.value != "") {
                var ans=($("#choose_answers li").last().length)?parseInt($("#choose_answers li").last().attr("id"))+1:1;
                $("#choose_answers").append('<li id="'+ans+'">'+this.value+'<span class="remove_answer" id="remove_'+ans+'">x</span></li>')
                $("#add_answers").append('<input type="hidden" name="answer[]" id="h_'+ans+'" value="'+this.value+'"/>')
                $(this).val("");  
            }
            return false;   
        }
    });
        /*remove answers*/
        $(document).delegate(".remove_answer","click",function(){
            var currentAns=this.id.split('_');
            $("#"+currentAns[1]).remove();
            $("#h_"+currentAns[1]).remove();
        });
        
        /*check some fields*/
        $("#campaignform").submit(function(){
            var cmpaign_type=$("#campaing_type").val();
            if (cmpaign_type==34) {
                if($.trim($("#question").val())==""){
                    $("#question").css("border","1px solid red");
                    return false
                }
                if($("#choose_answers li").last().length==0){
                    $("#add_answer").css("border","1px solid red");
                    return false
                }
                
            }else{
                if($("#media").val()==""){
                    $("#media").css("border","1px solid red");
                    return false
                }
            }
            
            if($("#cap_type").val()==3&&$("#finish_date").val()==""){
                $("#finish_date").css("border","1px solid red");
                return false;
            }
            
            <?php if($level<=2&&!isset($campigndata)):?>
            if($("#image").val()==""){
                $("#uniform-image").css("border","1px solid red");
                return false;
            }
        <?php endif;?>

        return true;
    });
        
        <?php if(isset($editing)):?>
        $("#campign_form").show();
        $("#uniform-image").show();
        $(".checker").show();

        <?php if($this->session->type_id<=2):?>
        $.each($('form').serializeArray(), function(index, value){
            $('input[name="' + value.name + '"]').attr('disabled', 'disabled');
            $('input[type="file"]').attr('disabled', 'disabled');
            $('select[name="' + value.name + '"]').attr('disabled', 'disabled');
            $("#hiddens").append("<input type='hidden' name='"+value.name+"' value='"+value.value+"'/>");
            $('input[type="hidden"][name="status"]').remove();
            $('select[name="status"]').removeAttr("disabled");
            $('input[type="hidden"]').removeAttr("disabled");
        });
    <?php endif;?>

<?php endif;?>

<?php if(isset($campigndata)&&$campigndata->status==1):?>
    $.each($('form').serializeArray(), function(index, value){
        $('input[name="' + value.name + '"]').attr('disabled', 'disabled');
        $('input[type="file"]').attr('disabled', 'disabled');
        $('select[name="'+ value.name +'"]').attr('disabled', 'disabled');
        $("#hiddens").append("<input type='hidden' name='"+value.name+"' value='"+value.value+"'/>");
        $('input[type="hidden"][name="status"]').remove();
        $('select[name="status"]').removeAttr("disabled");
        $('input[type="hidden"]').removeAttr("disabled");
    });
<?php endif;?>

$("#cap_type").change(function(){
  var cap = this.value;
  if(cap==3){
      $("#wrap_qty").css("display","none");
      $("#quantity").attr("disabled","disabled");
      $(".cant_cobro").hide();
  }else if(cap==2){
      $(".cobro").html("(Clicks)");
      $(".cant_cobro").show();
  }else{
      $("#wrap_qty").removeAttr("style");
      $("#quantity").removeAttr("disabled");
      $(".cant_cobro").show();
      $(".cobro").html("(Visualizaciones)");
  }
});

});
</script>
<div class="content">
    <div class="breadLine">
        <ul class="breadcrumb">
            <li><a href="<?php echo base_url() ?>admin">Administrador</a> <span class="divider">></span></li>
            <li class="active">Campaña <?php echo $tipo;?></li>
    </ul>
</div>
<div class="workplace">

    <?php if($level==3):?>
        <div class="row-fluid">
            <div class="span12">
                <div class="widgetButtons">
                    <?php 
                    if(isset($campaigns)&&$campaigns):
                        $c=1;
                    foreach($campaigns as $campaign):
                        $icon="";
                    switch($campaign->id){
                        case 31:
                        $icon="ibb-cloud";
                        break;
                        case 32:
                        $icon="ibb-video";
                        break;
                        case 33:
                        $icon="ibb-download";
                        break;
                        case 34:
                        $icon="ibb-text_document";
                        break;
                    }
                    if($c==5)
                        break;
                    ?>
                    <div class="bb gray">
                        <a href="javascript:void(0)" id="<?php echo $campaign->id?>" class="type_cap">
                            <span class="<?php echo $icon?>" ></span>
                        </a>
                        <div class="caption"><?php echo $campaign->name?></div>
                    </div>
                    <?php $c++;endforeach;endif;?>
                </div>
            </div>
            
        </div>
        <div class="dr">
          <span></span>
      </div>

      <?php $this->load->view('alerts/errors')?>
      <?php $this->load->view('alerts/success')?>


      <div class="row-fluid <?php if($level==3)echo "none"?>" id="campign_form">
        <div class="span12">
            <div class="head">
                <div class="isw-user"></div>
                <h1>
                    <?php echo "Campaña ".$tipo;?><span id="ltipo"></span>
                </h1>
                <div class="clear"></div>
            </div>
            <div class="block-fluid"> 
                <form id="campaignform" method="post" enctype="multipart/form-data">
                    <div class="row-form no_video">
                        <img src="<?php  echo (isset($campigndata)&&$campigndata->image!=null)?base_url("assets/media/campaign/".$campigndata->image):base_url("assets/media/campaign/default.png")?>"
                        alt="<?php echo isset($campigndata)?$campigndata->name:"campaña"?>"
                        title="<?php echo isset($campigndata)?$campigndata->name:"campaña"?>" 
                        class="img-circle img-thumbnail" width="150" height="150"/>
                    </div>
                    <div class="row-form">
                        <div class="span3">Nombre:</div>
                        <div class="span9"><input type="text" id="name" name="name" placeholder="Nombre" value="<?php echo set_value('name',isset($campigndata)?$campigndata->name:''); ?>"/></div>
                        <div class="clear"></div>
                    </div>
                    <div class="row-form no_video">
                       <div class="span3">Imágen campaña:</div>
                       <div class="span9"><input type="file" class="form-control-file" id="image" name="image"></div>
                       <small class="text-muted">Solo se permite archivos de tipo jpg|png|gif.</small>
                       <div class="clear"></div>
                   </div>
                   <div class="row-form">
                    <div class="span3">Tipo de cobro</div>
                    <div class="span7">
                        <select id="cap_type" name="cap_type">
                            <option value="">--Seleccionar--</option>
                            <option value="1" <?php echo (isset($campigndata)&&$campigndata->cap_type==1||(isset($_POST["cap_type"])&&$_POST["cap_type"]==1))?"selected":""?>>Visualizaciones</option>
                            <option value="2" <?php echo (isset($campigndata)&&$campigndata->cap_type==2||(isset($_POST["cap_type"])&&$_POST["cap_type"]==2))?"selected":""?>>Clicks</option>
                            <option value="3" <?php echo (isset($campigndata)&&$campigndata->cap_type==3||(isset($_POST["cap_type"])&&$_POST["cap_type"]==3))?"selected":""?>>Fecha de vencimiento</option>
                        </select>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="row-form cant_cobro" id="wrap_qty">
                    <div class="span3 tt" title="Cantidad de cobro sea por visualizacion o por clicks">Cantidad de tipo cobro <span class="cobro"></span></div>
                    <div class="span9"><input type="text" id="quantity" name="quantity" placeholder="Cantidad de tipo cobro" value="<?php echo set_value('quantity',isset($campigndata)?$campigndata->quantity:''); ?>"/></div>
                    <div class="clear"></div>
                </div>
                <div class="row-form">
                    <div class="span3">Monto disponible $:</div>
                    <div class="span3"><input type="text" id="amount" name="amount" placeholder="Monto disponible" value="<?php echo set_value('amount',isset($campigndata)?$campigndata->amount:''); ?>"/></div>
                    <div class="span3" id="sumary_price"></div>

                    <div class="clear"></div>
                </div> 
                <div class="row-form">
                    <div class="span3">Fecha inicio:</div>
                    <div class="span5"><input type="text" class="from" id="start_date" name="start_date" placeholder="Fecha inicio" value="<?php echo set_value('start_date',isset($campigndata)?$campigndata->start_date:''); ?>"/></div>
                    <div class="clear"></div>
                </div>
                <div class="row-form">
                    <div class="span3">Fecha fin:</div>
                    <div class="span5"><input type="text" class="to" id="finish_date" name="finish_date" placeholder="Fecha fin" value="<?php echo set_value('finish_date',isset($campigndata)?$campigndata->finish_date:''); ?>"/></div>
                    <div class="clear"></div>
                </div>

                <div class="row-form">
                    <div class="span3">Audiencia genero:</div>
                    <div class="span5">
                       <select id="filter1" name="filter1">
                        <option value="">--Seleccionar--</option>
                        <?php 
                        if(isset($filtersGenero)&&$filtersGenero):
                            for($i=0;$i<=2;$i++):
                                ?>
                            <option value="<?php echo $filtersGenero[$i]->id?>" <?php echo (isset($filter[$filtersGenero[$i]->id])||(isset($_POST["filter1"])&&$_POST["filter1"]==$filtersGenero[$i]->id))?'selected':''?>><?php echo $filtersGenero[$i]->name?></option>
                        <?php endfor;endif;?>
                    </select>
                </div>
                <div class="clear"></div>
            </div>
            <div class="row-form">
                <div class="span3">Audiencia edad:</div>
                <div class="span5">
                    <?php 
                    if(isset($filtersEdad)&&$filtersEdad):
                        for($i=3;$i<count($filtersEdad);$i++):
                            ?>

                        <label class="checkbox inline " >
                            <div class="checker">
                                <span class="checked">
                                    <input type="checkbox" name="filter2[]" value="<?php echo $filtersEdad[$i]->id?>" <?php echo isset($filter[$filtersEdad[$i]->id])?'checked':''?> style="opacity: 0;">
                                </span>
                            </div>
                            <?php echo $filtersEdad[$i]->name?>
                        </label>

                    <?php endfor;endif;?>
                </div>
                <div class="clear"></div>
            </div>

            <div class="row-form">
                <div class="span3">Puntos de red:</div>
                <div class="span7">
                    <?php 
                    if(isset($hotspots)&&$hotspots):
                        foreach($hotspots as $hotspot):
                            ?>
                        <label class="checkbox inline tt" title="Valor pauta día: $<?php echo number_format($hotspot->day_amount);?>,
                            valor pauta por click: $<?php echo number_format($hotspot->click_amount)?>, valor pauta por visualizacion $<?php echo number_format($hotspot->print_amount)?>">
                            <div class="checker">
                                <span class="checked">
                                    <input type="checkbox" name="hotspot[]" value="<?php echo $hotspot->id?>" <?php echo isset($spots[$hotspot->id])?'checked="checked"':''?> style="opacity: 0;">
                                </span>
                            </div>
                            <?php echo $hotspot->name?> 
                        </label>
                    <?php endforeach;endif;?>
                </div>
                <div class="clear"></div>
            </div>

            <div id="cap_media" <?php if(isset($campigndata)&&$campigndata->campaing_type==34) {?>class="none"<?php }?>>
                <div class="row-form">
                    <div class="span3">Url:</div>
                    <div class="span9"><input type="text" id="media" name="media" placeholder="Url" value="<?php echo set_value('media',isset($campigndata)?$campigndata->media:''); ?>"/></div>
                    <div class="clear"></div>
                </div> 
                <div class="row-form">
                    <div class="span3">Descripción link (app,sitio web o video):</div>
                    <div class="span9"><textarea name="description_media" id="description_media"><?php echo set_value('description_meida',isset($campigndata)?$campigndata->description_media:''); ?></textarea></div>
                    <div class="clear"></div>
                </div>
            </div>

            <div id="cap_survey" class="encuesta">
                <div class="row-form">
                    <div class="span3">Pregunta:</div>
                    <div class="span9"><textarea name="question" id="question"><?php echo set_value('question',isset($campigndata)?$campigndata->question:''); ?></textarea></div>

                    <div class="clear"></div>
                </div>
                <div class="row-form encuesta">
                    <div class="span3">Respuestas:</div>
                    <div class="span9" id="add_answers">
                        <input type="text" class="answer" id="add_answer" name="add_answer" placeholder="Ingrese la respuesta y haga enter para agregar otra respuesta" value=""/>
                        <ol class="answers_list sList" id="choose_answers">
                            <?php
                            if (isset($answers) && $answers):
                                foreach ($answers as $answer):
                                    ?>
                                <li id="<?php echo $answer->id ?>"><?php echo $answer->answer ?><span id="remove_<?php echo $answer->id ?>" class="remove_answer">x</span></li>
                                <?php 
                                endforeach;
                                ?>
                            </ol>
                            <?php
                            foreach ($answers as $answer):
                                ?>
                            <input id="h_<?php echo $answer->id ?>" type="hidden" value="<?php echo $answer->answer ?>" name="answer[]">
                            <?php 
                            endforeach;
                            endif 
                            ?>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
                <?php if($this->session->type_id<=2):?>
                    <div class="row-form">
                        <div class="span3">Estado</div>
                        <div class="span7">
                            <select id="status" name="status">
                                <option value="">--Seleccionar--</option>
                                <option value="0" <?php echo (isset($campigndata) && $campigndata->status == 0||(isset($_POST["status"])&&$_POST["status"]==0)) ? "selected" : "" ?>>Pendiente</option>
                                <option value="1" <?php echo (isset($campigndata) && $campigndata->status == 1||(isset($_POST["status"])&&$_POST["status"]==1)) ? "selected" : "" ?>>Activa</option>
                                <option value="2" <?php echo (isset($campigndata) && $campigndata->status == 2||(isset($_POST["status"])&&$_POST["status"]==2)) ? "selected" : "" ?>>Rechazar</option>
                                <option value="3" <?php echo (isset($campigndata) && $campigndata->status == 3||(isset($_POST["status"])&&$_POST["status"]==3)) ? "selected" : "" ?>>Cerrar</option>
                            </select>
                        </div>

                        <div class="clear"></div>
                    </div>
                <?php endif?>

                <div class="row-form">
                    <div id="hiddens"></div>

                    <input type="hidden" name="level" value="<?php echo $level?>"/>
                    <input type="hidden" name="campaing_type" value="<?php echo isset($campigndata)?$campigndata->campaing_type:"0"?>" id="campaing_type"/>
                    <input type="hidden" name="action" value="<?php echo isset($campigndata)?"edit":"new"?>"/>
                    <button type="submit" class="btn btn-primary"><?php echo isset($campigndata)?"Editar":"Crear"?></button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif;//end level 3 form?>


<?php if($level<=2):?>
    <?php $this->load->view('alerts/errors')?>
    <?php $this->load->view('alerts/success')?>
    <div class="row-fluid">
        <div class="span12">
            <div class="head">
                <div class="isw-user"></div>
                <h1>
                    <?php echo "Campaña ".$tipo;?>
                </h1>
                <div class="clear"></div>
            </div>
            <div class="block-fluid"> 
                <form id="campaignform" method="post" enctype="multipart/form-data">

                    <div class="row-form">
                        <img src="<?php  echo (isset($campigndata)&&$campigndata->image!=null)?base_url("assets/media/campaign/".$campigndata->image):base_url("assets/media/campaign/default.png")?>"
                        alt="<?php echo isset($campigndata)?$campigndata->name:"campaña"?>"
                        title="<?php echo isset($campigndata)?$campigndata->name:"campaña"?>" 
                        class="img-circle img-thumbnail" width="150" height="150"/>
                    </div>

                    <div class="row-form">
                        <div class="span3">Segundos de duración:</div>
                        <div class="span9"><input type="text" id="duration" name="duration" placeholder="Segundos" value="<?php echo set_value('duration',isset($campigndata)?$campigndata->duration:''); ?>"/></div>
                        <div class="clear"></div>
                    </div> 
                    <?php if($level==2):?>
                        <div class="row-form">
                            <div class="span3">Audiencia genero:</div>
                            <div class="span5">
                               <select  id="filter1" name="filter1">
                                <option value="">--Seleccionar--</option>
                                <?php 
                                if(isset($filters)&&$filters):
                                    for($i=0;$i<=2;$i++):
                                        ?>
                                    <option value="<?php echo $filters[$i]->id?>" <?php echo isset($filter[$filters[$i]->id])?'selected':''?>><?php echo $filters[$i]->name?></option>
                                <?php endfor;endif;?>
                            </select>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="row-form">
                        <div class="span3">Audiencia edad:</div>
                        <div class="span5">
                           <select  id="filter2" name="filter2">
                            <option value="">--Seleccionar--</option>
                            <?php 
                            if(isset($filters)&&$filters):
                                for($i=3;$i<count($filters);$i++):
                                    ?>
                                <option value="<?php echo $filters[$i]->id?>" <?php echo isset($filter[$filters[$i]->id])?'selected':''?>><?php echo $filters[$i]->name?></option>
                            <?php endfor;endif;?>
                        </select>
                    </div>
                    <div class="clear"></div>
                </div>
            <?php endif;?>
            <div class="row-form">
               <div class="span3">Imagen campaña:</div>
               <div class="span9"><input type="file" class="form-control-file" id="image" name="image"></div>
               <small class="text-muted">Solo se permite archivos de tipo jpg|png|gif.</small>
               <div class="clear"></div>
           </div>
           <div class="row-form">
            <div class="span3">Puntos de red:</div>
            <div class="span7">
                <?php 
                if(isset($hotspots)&&$hotspots):
                    foreach($hotspots as $hotspot):
                        ?>
                    <label class="checkbox inline tt" title="Valor pauta día: $<?php echo number_format($hotspot->day_amount);?>,
                        valor pauta por click: $<?php echo number_format($hotspot->click_amount)?>, valor pauta por visualizacion $<?php echo number_format($hotspot->print_amount)?>">
                        <div class="checker">
                            <span class="checked">
                                <input type="checkbox" name="hotspot[]" value="<?php echo $hotspot->id?>" <?php echo isset($spots[$hotspot->id])?'checked="checked"':''?> style="opacity: 0;">
                            </span>
                        </div>
                        <?php echo $hotspot->name?> 
                    </label>
                <?php endforeach;endif;?>
            </div>
            <div class="clear"></div>
        </div>
        <?php if($this->session->type_id<=2):?>
            <div class="row-form">
                <div class="span3">Estado</div>
                <div class="span7">
                    <select  id="status" name="status" <?php echo isset($avoidDataTable) ? "disabled" : "" ?>>
                        <option value="">--Seleccionar--</option>
                        <option value="0" <?php echo (isset($campigndata) && $campigndata->status == 0) ? "selected" : "" ?>>Pendiente</option>
                        <option value="1" <?php echo (isset($campigndata) && $campigndata->status == 1) ? "selected" : "" ?>>Activa</option>
                        <option value="2" <?php echo (isset($campigndata) && $campigndata->status == 2) ? "selected" : "" ?>>Rechazar</option>
                        <option value="3" <?php echo (isset($campigndata) && $campigndata->status == 3) ? "selected" : "" ?>>Cerrar</option>

                    </select>
                </div>

                <div class="clear"></div>
            </div>
        <?php endif?>
        <div class="row-form">
         <div id="hiddens"></div>
         <input type="hidden" name="level" value="<?php echo $level?>"/>
         <input type="hidden" name="action" value="<?php echo isset($campigndata)?"edit":"new"?>"/>
         <button type="submit" class="btn btn-primary"><?php echo isset($campigndata)?"Editar":"Crear"?></button>
     </div>
 </form>
</div>
</div>
</div>
<?php endif;//end level 1 and 2 form?>


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