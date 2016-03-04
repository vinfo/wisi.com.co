<?php
include 'config.php';
$table = 'hotspot';
// Table's primary key
$primaryKey = 'id';

$columns = array(
	array( 'db' => 'h.name', 'dt' => 'name', 'field' => 'name','joinName'=>'h.name'),
	array( 'db' => 'h.location','dt' => 'location','field' => 'location','joinName'=>'h.location' ),
	array( 'db' => 'h.serial', 'dt' => 'serial','field' => 'serial','joinName'=>'h.serial'),
        array( 'db' => 't.name AS hardware', 'dt' => 'hardware', 'field' => 'hardware','joinName'=>'t.name'),
	array(
		'db'        => 'h.cdate',
		'dt'        => 'cdate',
		'formatter' => function( $d, $row ) {
                    setlocale(LC_TIME, "spanish"); 
                    $format ='%A '.((strtoupper(substr(PHP_OS, 0, 3)) == 'WIN') ? '%#d' : '%e').' de %B del %Y';
                    return utf8_encode(strftime($format, strtotime($d)));
		},
               'field' => 'cdate',
               'joinName'=>'h.cdate'
	),
        
        array(
            'db'=>'h.id', 
            'dt'=>'action',
            'formatter'=>function($d,$row){
                $canDelete=($_GET['role']==1)?"<a href='javascript:void(0)' title='Eliminar' class='delete_item' id='item_".$d."'><i class=\"icon-remove\"></i></a>":"";
                
                $innerAction="<div class=\"datatable_action_buttons\"><input type=\"checkbox\" class=\"selectItem\" name=\"selectItem[]\" id=\"item_".$d."\"  value=\"".$d."\"/>"
                            . "<a href='http://".$_SERVER['HTTP_HOST']."/hotspot/".$d."/' title='Editar'><i class=\"icon-pencil\"></i></a>"
                            . $canDelete."</div>";   
                return $innerAction;
            },
          'field' => 'id',
          'joinName'=>'h.id'
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
$joinQuery = "FROM hotspot AS h INNER JOIN type AS t ON (t.id = h.hardware)";  

echo json_encode(
	SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns,$joinQuery)
);






