<?php
include 'config.php';
$table = 'message';
// Table's primary key
$primaryKey = 'id';



$columns = array(
	array( 'db' => 'm.message', 'dt' => 'message', 'field' => 'message','joinName'=>'m.message'),
        array( 'db' => 't.name AS type', 'dt' => 'type', 'field' => 'type','joinName'=>'t.name'),
    
        array(
		'db'        => 'm.start_date',
		'dt'        => 'start_date',
		'formatter' => function( $d, $row ) {
                    setlocale(LC_TIME, "spanish"); 
                    $format ='%A '.((strtoupper(substr(PHP_OS, 0, 3)) == 'WIN') ? '%#d' : '%e').' de %B del %Y';
                    return utf8_encode(strftime($format, strtotime($d)));
		},
               'field' => 'start_date',
               'joinName'=>'m.start_date'
	),
        array(
		'db'        => 'm.finish_date',
		'dt'        => 'finish_date',
		'formatter' => function( $d, $row ) {
                    setlocale(LC_TIME, "spanish"); 
                    $format ='%A '.((strtoupper(substr(PHP_OS, 0, 3)) == 'WIN') ? '%#d' : '%e').' de %B del %Y';
                    return utf8_encode(strftime($format, strtotime($d)));
		},
               'field' => 'finish_date',
               'joinName'=>'m.finish_date'
	),
	array(
		'db'        => 'm.cdate',
		'dt'        => 'cdate',
		'formatter' => function( $d, $row ) {
                    setlocale(LC_TIME, "spanish"); 
                    $format ='%A '.((strtoupper(substr(PHP_OS, 0, 3)) == 'WIN') ? '%#d' : '%e').' de %B del %Y';
                    return utf8_encode(strftime($format, strtotime($d)));
		},
               'field' => 'cdate',
               'joinName'=>'m.cdate'
	),
        
        array(
            'db'=>'m.id', 
            'dt'=>'action',
            'formatter'=>function($d,$row){
                $canDelete=($_GET['role']==1)?"<a href='javascript:void(0)' title='Eliminar' class='delete_item' id='item_".$d."'><i class=\"icon-remove\"></i></a>":"";
                
                
                $innerAction="<div class=\"datatable_action_buttons\"><input type=\"checkbox\" class=\"selectItem\" name=\"selectItem[]\" id=\"item_".$d."\"  value=\"".$d."\"/>"
                            . "<a href='http://".$_SERVER['HTTP_HOST']."/admin/message/".$d."/' title='Editar'><i class=\"icon-pencil\"></i></a>"
                            . $canDelete."</div>";   
                return $innerAction;
            },
          'field' => 'id',
          'joinName'=>'m.id'
        )
	
);
// SQL server connection information
$sql_details = array(
	'user' => $db_user,
	'pass' => $db_password,
	'db'   => $db_name,
	'host' => $db_host
);

require('ssp.class.php');
$joinQuery = "FROM message AS m INNER JOIN type AS t ON (t.id = m.type_id)";  

echo json_encode(
	SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns,$joinQuery)
);






