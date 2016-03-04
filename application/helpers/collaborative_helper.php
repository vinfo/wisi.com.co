<?php
function paginate($num_per_page,$num_rows,$url,$segment){
    $CI =& get_instance();
    $CI->load->library('pagination');
        $config['base_url'] = $url;
        $config['total_rows'] =$num_rows;
        $config['per_page'] = $num_per_page; 
        $config['uri_segment'] = 4;//$segment;
        $config['num_links'] = 5; 
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a>';
        $config['cur_tag_close'] = '</a></li>';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['next_link'] = 'Siguiente;';
        $config['prev_link'] = 'Anterior;';
        $config['last_link'] = FALSE;
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['first_link'] = TRUE;
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        
        $CI->pagination->initialize($config); 
        return $CI->pagination->create_links();
    }
function clean_url($url){
	$find = array('&oacute;','#',' ','@','.','+','<','/','Ñ','ñ','\'','(',')','?','¿','"',':','á','é','í','ó','ú','%',',');
	$new = array('o','numero','-','-','','','','-','N','n','-','','','','','','','a','e','i','o','u','','');
	for ($i = 0; $i < count($find); $i++){
		$findcache[] = $find[$i];
	}
        $url=stripAccents($url);
	$url = utf8_decode($url);
	$link = str_replace($findcache, $new, $url);
	$link = strtolower($link);
	return utf8_encode($link);
}

function stripAccents($string){
    $deny= array ("á","é","í","ó","ú","Á","É","Í","Ó","Ú","ñ","À","Ã","Ì","Ò","Ù","Ã™","Ã ","Ã¨","Ã¬","Ã²","Ã¹","ç","Ç","Ã¢","ê","Ã®","Ã´","Ã»","Ã‚","ÃŠ","ÃŽ","Ã”","Ã›","ü","Ã¶","Ã–","Ã¯","Ã¤","«","Ò","Ã","Ã„","Ã‹");
    $allow= array ("a","e","i","o","u","A","E","I","O","U","n","N","A","E","I","O","U","a","e","i","o","u","c","C","a","e","i","o","u","A","E","I","O","U","u","o","O","i","a","e","U","I","A","E");
    $srt= str_replace($deny, $allow ,$string);
    return $srt;
}

function sendEmail($form,$name,$to,$subject,$body,$cc=false,$bcc=false,$reply=false){
        $CI =& get_instance();
        $CI->load->library('email');
        $config['crlf'] = "\r\n";
        $config['newline'] = "\r\n";
        $config['protocol'] = 'sendmail';
        $config['mailpath'] = '/usr/sbin/sendmail';
        $config['charset'] = 'utf-8';
        $config['mailtype'] = 'html';
        $config['wordwrap'] = TRUE;
        $CI->email->clear();
        $CI->email->initialize($config);
        $CI->email->from($form,$name);
        $CI->email->to($to);
        if($cc){$CI->email->cc($cc);}
        if($bcc){$CI->email->bcc($bcc);}
        if($reply){$CI->email->reply_to($reply[0],$reply[1]);}
        $CI->email->subject($subject);
        $CI->email->message($body);
        //echo $CI->email->print_debugger();
       
        if($CI->email->send()){
            return true;
        }else{
            return false;
        }
    }

function GetIpUser(){
    $ip = "";
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

function formatDate($data){
    setlocale(LC_TIME, "spanish"); 
    return utf8_encode(strftime('%A %e de %B del %Y', strtotime($data))); 
    
}
/*obtener imagen de usuario*/
function GetUserImage($imgpath,$size) {
    if ($imgpath != "") {
        if (preg_match("%^((https?://)|(www\.)|(http?://))([a-z0-9-].?)+(:[0-9]+)?(/.*)?$%i", $imgpath)){
            return $imgpath;
        } else {
            if($size=="big"){
                return base_url() . "assets/media/user/" . $imgpath;
                
            }else{
                return base_url() . "assets/media/user/thumb/" . $imgpath;
            }
        }
    }else {
        return base_url() . "assets/media/user/default.png";
    }
}
/*borrrar directorios y archivos*/
function delTreeFolder($dir) { 
   $files = array_diff(scandir($dir), array('.','..')); 
    foreach ($files as $file) { 
      (is_dir("$dir/$file")) ? delTreeFolder("$dir/$file") : unlink("$dir/$file"); 
    } 
    return rmdir($dir); 
  } 
/*ckeditor instancia*/
function wysiwyg($textarea,$toolbar="basic"){
    include("vendor/ckeditor/wysiwyg.php");
}

/*errores de formularios */
function error_array(array $errorvalues=array()){
    $CI =& get_instance();
    return $CI->form_validation->error_array();
}
