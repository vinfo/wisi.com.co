<?php
include 'config.php';
$table = 'user';
// Table's primary key
$primaryKey = 'id';



$columns = array(
	array( 'db' => 'u.name', 'dt' => 'firstname', 'field' => 'name','joinName'=>'u.name'),
	array( 'db' => 'u.lastname','dt' => 'lastname','field' => 'lastname','joinName'=>'u.lastname' ),
	array( 'db' => 'u.email', 'dt' => 'email','field' => 'email','joinName'=>'u.name'),
        array( 'db' => 't.name AS type', 'dt' => 'type', 'field' => 'type','joinName'=>'t.name'),
	array(
		'db'        => 'u.cdate',
		'dt'        => 'cdate',
		'formatter' => function( $d, $row ) {
                    setlocale(LC_TIME, "spanish"); 
                    $format ='%A '.((strtoupper(substr(PHP_OS, 0, 3)) == 'WIN') ? '%#d' : '%e').' de %B del %Y';
                    return utf8_encode(strftime($format, strtotime($d)));
		},
               'field' => 'cdate',
               'joinName'=>'u.cdate'
	),
        
        array(
            'db'=>'u.id', 
            'dt'=>'action',
            'formatter'=>function($d,$row){
                $urlType="admin";
                $canDelete=($_GET['role']==1)?"<a href='javascript:void(0)' title='Eliminar' class='delete_item' id='item_".$d."'><i class=\"icon-remove\"></i></a>":"";
                if(isset($_GET['current_type'])){
                    switch ($_GET['current_type']) {
                        case 1:
                        case 2:
                            $urlType="admin";
                            break;
                        case 3:
                            $urlType = "client";
                            break;
                        case 4:
                            $urlType = "advertiser";
                            break;
                    }
                }
                $check="<input type=\"checkbox\" class=\"selectItem\" name=\"selectItem[]\" id=\"item_".$d."\"  value=\"".$d."\"/>";
                if($urlType=="advertiser")$check="";               
                $innerAction="<div class=\"datatable_action_buttons\">".$check
                            . "<a href='http://".$_SERVER['HTTP_HOST']."/user/".$urlType."/".$d."/' title='Editar'><i class=\"icon-pencil\"></i></a>"
                            . $canDelete."</div>";   
                return $innerAction;
            },
          'field' => 'id',
          'joinName'=>'u.id'
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
$joinQuery = "FROM user AS u INNER JOIN type AS t ON (t.id = u.type_id)";
$extraWhere = isset($_GET['current_type'])?"t.id=".$_GET['current_type']:"";   
if(isset($_GET['current_type'])&&$_GET['current_type']==1){
    $extraWhere="t.id<=2";
}
echo json_encode(
	SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns,$joinQuery,$extraWhere)
);




