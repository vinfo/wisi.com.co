<?php defined('BASEPATH') OR exit('No direct script access allowed');
/*
 * @author pochis
 * @year 2016
 * request method to validate control authentication
 */
class Auth {
    protected $CI;
    
    public function __construct() {
        $this->CI =& get_instance();
        
       
        if($this->CI->uri->segment(1)=="login" &&$this->checkSession()){
            redirect("/");
        }
        if($this->CI->uri->segment(1)!="login" &&!$this->checkSession()&&!$this->CI->uri->segment(1)=="recovery"){
             redirect("login");
        }        
    }
    /*
     * @method login
     * @param username
     * @param password
     * @param rol
     * @return bool
     * autenticador de usuario para la creacion de session del mismo
     */
    public function login($username,$password,$rol){
        
            $query=$this->CI->db->select('u.*')
                ->from('user AS u')
                ->where('u.password',$password)
                ->where('u.status',1)
                //->where('u.type_id',$rol)
                ->where('u.email',$username)
                ->join('type AS t','t.id=u.type_id','INNER')
                ->group_by('u.id')
                ->get('user');
            if ($query->num_rows()>0) {
                $this->_createSession($query->row());
                return true;
            } else {
                return false;
            }
    }
    /*
     * @method recovery
     * @param email
     * @param rol
     * @return bool
     * verificacion  de correo para la reasignacion de contraseña nueva
     */
    public function recovery($email){
        $query =$this->CI->db->select('id')
                ->where('email',$email)
                ->where('status',1)
                ->where('type_id <>',2)
                ->get('user');
        
        if ($query->num_rows()>0) {
            if($this->_createUserNewPassword($query->row()->id,$email)){
                return true;
                
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
    
    /*
     * @method checkSession
     * @return bool
     * verificacion  session activa de usuario
     */
    public function checkSession(){
        if($this->CI->session->id != ''&&$this->CI->session->session_id!=''){
            return true;
        }else{
            return false;
        }
    }
    /*
     * @method logout
     * @return bool
     * cerrar  session activa de usuario
     */
    public function logout(){
        $this->CI->session->sess_destroy();
    }
    /*
     * @method _createSession
     * @return void
     * crear session de usuario
     */
    private function _createSession($sessionArray){
       $this->CI->session->set_userdata(get_object_vars($sessionArray));
    }
    /*
     * @method _createUserNewPassword
     * @return bool
     * restablecer la contraseña y enviar correo  de la nueva contraseña a l usuario
     */
    private function _createUserNewPassword($id_user,$email){
        $this->CI->load->helper('string');
        $newPass =random_string('alnum', 8);
        $prepend = array(
            'password' => sha1($newPass)
        );
        $update =$this->CI->db->where('id',$id_user)->update('user',$prepend);
        if ($update) {
            $body ='<p>Su nueva contraseña ha sido creada y asiganada coorrectamenter</p>';
            $body.='<p>Por favor da click  en el siguiente  link  para ingresar de nuevo al panel administrativo.</p>';
            $body.='<p>Su contraseña nueva es <strong>' . $newPass . '</strong></p>';
            $body.='<p><a href="' . base_url() . '">' . base_url() . 'admin/</a></p>';
            $body.='<p>Gracias</p>';

            $send = sendEmail("info@wisi.com.co", 
                              "Panel administrativo", 
                              $email, 
                              "Recuperacion de contraseña",
                              $body);
            return true;
        }else{
            return false;
        }
    }
  
}