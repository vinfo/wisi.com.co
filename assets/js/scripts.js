var collaborative={
    base_url:'http://'+location.host+'/',
   
    iniDatatable:function(id_table,objfields,serverpath){
       
       var oTable= $('#'+id_table).dataTable({
                        bJQueryUI: true,
                        bDestroy: true,
                        iDisplayLength: 10,
                        bFilter: true,
                        stateSave: true,
                        "sPaginationType": "full_numbers",
                        "oLanguage": {
                            "sLengthMenu": "Mostrando _MENU_ entradas por pagina",
                            "sZeroRecords": "Sin entradas por mostrar",
                            "sInfo": "Mostrando _START_ a _END_ de _TOTAL_ entradas",
                            "sInfoEmpty": "Mostrando 0 a 0 de 0 entradas",
                            "sInfoFiltered": "(filtrado de _MAX_ total entradas)",
                            "sProcessing": "Procesando",
                            "sSearch": "Buscar:",
                            "oPaginate": {
                                "sFirst": "Primera",
                                "sLast": "Ultima",
                                "sNext": "Siguiente",
                                "sPrevious": "Anterior"
                            },
                        },
                        "processing": true,
                        "serverSide": true,
                        "ajax": collaborative.base_url+serverpath,
                        "columns":objfields ,

                    });
            return oTable;
                        
    },
    selectAllCheckboxes:function(trigger,toselect){
         $('#'+trigger).click(function(event) {
            if(this.checked) { 
                $('.'+toselect).each(function() { 
                    this.checked = true;                
                });
            }else{
                $('.'+toselect).each(function() { 
                    this.checked = false;                       
                });         
            }
      });
   },
   deleteItem:function(table,objids,successId,errorId){
       var plurSuccessMsg=(objids.length>1)?"Los items fueron eliminados":"El item fue eliminado";
      $.post(collaborative.base_url+"serve/deleteItems",{action:"deleteItem",data:objids,table:table},function(res){
            if (res.status=="ok") {
                $("#"+errorId).hide(); 
                $("#"+successId).html(plurSuccessMsg);
                $("#"+successId).show();
                $("html, body").animate({scrollTop: $('#'+successId).offset().top }, 1000);
                setInterval(function(){ window.location.href=window.location.href }, 2000);
            }else{
                $("#"+successId).hide();
                $("#"+errorId).html("Ha ocurrido un error inesperado, vuelve a intentarlo mas tarde");
                $("#"+errorId).show();
                $("html, body").animate({scrollTop: $('#'+errorId).offset().top }, 2000);
               
            }
       });
   }
}