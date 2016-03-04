<?php
include 'config.php';
$table = 'campaign';
// Table's primary key
$primaryKey = 'id';



$columns = array(
	array( 'db' => 'c.name', 'dt' => 'name', 'field' => 'name','joinName'=>'c.name'),
	array( 'db' => 'c.views', 'dt' => 'views','field' => 'views','joinName'=>'c.views'),
        array( 'db' => 'c.clicks', 'dt' => 'clicks','field' => 'clicks','joinName'=>'c.clicks'),
        array( 'db' => 'c.quantity', 'dt' => 'quantity','field' => 'quantity','joinName'=>'c.quantity'),
        array( 'db' => 'c.level', 'dt' => 'level', 'field' => 'type','joinName'=>'c.level'),
        array( 'db' => 't.name AS type', 'dt' => 'type', 'field' => 'type','joinName'=>'t.name'),
	array(
		'db'        => 'c.start_date',
		'dt'        => 'start_date',
		'formatter' => function( $d, $row ) {
                    setlocale(LC_TIME, "spanish"); 
                    $format ='%A '.((strtoupper(substr(PHP_OS, 0, 3)) == 'WIN') ? '%#d' : '%e').' de %B del %Y';
                    return ($row['level']<=2)?"N/A":utf8_encode(strftime($format, strtotime($d)));
		},
               'field' => 'start_date',
               'joinName'=>'c.start_date'
	),
        array(
		'db'        => 'c.finish_date',
		'dt'        => 'finish_date',
		'formatter' => function( $d, $row ) {
                    setlocale(LC_TIME, "spanish"); 
                    $format ='%A '.((strtoupper(substr(PHP_OS, 0, 3)) == 'WIN') ? '%#d' : '%e').' de %B del %Y';
                    if($row['finish_date']<>'0000-00-00 00:00:00')
                        return ($row['level']<=2)?"N/A":utf8_encode(strftime($format, strtotime($d)));
                    else
                        return "N/A";
		},
               'field' => 'finish_date',
               'joinName'=>'c.finish_date'
	),
        
        array(
            'db'=>'c.id', 
            'dt'=>'action',
            'formatter'=>function($d,$row){
                $canDelete=($_GET['role']==1)?"<a href='javascript:void(0)' title='Eliminar' class='delete_item' id='item_".$d."'><i class=\"icon-remove\"></i></a>":"";
                $urlType='';
                switch ($row['level']) {
                        case 1:
                            $urlType="global";
                            break;
                        case 2:
                            $urlType = "connection";
                            break;
                        default:
                            $urlType = "action";
                            break;
                }
                $stats='';
                if(isset($_GET['status'])){
                    $stats='<a title="Estadisticas de la campaña"><i class="icon-signal"></i></a>';
                }
                $innerAction="<div class=\"datatable_action_buttons\"><input type=\"checkbox\" class=\"selectItem\" name=\"selectItem[]\" id=\"item_".$d."\"  value=\"".$d."\"/>"
                            . "<a href='http://".$_SERVER['HTTP_HOST']."/campaign/".$urlType."/".$d."/' title='Editar'><i class=\"icon-pencil\"></i></a>"
                            . $canDelete.$stats."</div>";   
                return $innerAction;
            },
          'field' => 'id',
          'joinName'=>'c.id'
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
$extraWhere='';
$joinQuery='';

if($_GET['role']<=2){
    $joinQuery = "FROM campaign AS c INNER JOIN type AS t ON (t.id = c.campaing_type)";
}else{
    $joinQuery = "FROM campaign AS c INNER JOIN type AS t ON (t.id = c.campaing_type) "
                . "INNER JOIN campaign_has_user AS cu ON cu.campaign_id=c.id ";
    $extraWhere = "cu.user_id=".$_GET['user'];
}
if(isset($_GET['status'])){
    $extraWhere .= " AND c.status=".$_GET['status'];
}

echo json_encode(
	SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns,$joinQuery,$extraWhere)
);

