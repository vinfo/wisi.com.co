<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/*
 * @author pochis
 * @year 2016
 * request method to validate any submitted information
 */

class Request_validation {
    
   protected $CI;
   protected $isValid=true;
   
   public function __construct() {
     $this->CI =& get_instance();
     $this->CI->load->library('form_validation');
   }
    
   
   /*
    * @method login_request
    * request for login
    * @return bool
    */
   public function login_request(){
        $this->CI->form_validation->set_rules('email', 'Correo electronico', 'required|valid_email',
                  array(
                      'required' => '%s es requerido',
                      'valid_email'=>'El %s debe ser valido'
                      )
                 );
        $this->CI->form_validation->set_rules('password', 'Contraseña', 'required', array('required' => '%s es requerida'));
         
        if ($this->CI->form_validation->run() == FALSE)
               return false;
        
        $this->CI->form_validation->reset_validation(); 
        return true;
    }
    /*
     * @method user_request
     * @param type|new|edit|
     * request for user form
     * @return bool
     */
    public function user_request($type,$role){
        
        $this->CI->form_validation->set_rules('name', 'Nombre', 'required', array(
                'required' => '%s es requerido',
                'valid_email' => 'El %s debe ser valido'
                    )
        );
        $this->CI->form_validation->set_rules('lastname', 'Apellido', 'required', array(
            'required' => '%s es requerido',
            'valid_email' => 'El %s debe ser valido'
                )
        );
        if($type=="new")
            $this->CI->form_validation->set_rules('email', 'Correo electronico', 'required|valid_email|is_unique[user.email]', array(
                'required' => '%s es requerido',
                'valid_email' => 'El %s debe ser valido',
                'is_unique' => 'El %s ya esta asignado a una cuenta'
                   )
            );

        $this->CI->form_validation->set_rules('type_id', 'Tipo de usuario', 'required', array('required' => '%s es requerido'));
        $this->CI->form_validation->set_rules('status', 'Estado de usuario', 'required', array('required' => '%s es requerido'));

        if($type=="new"){
              $this->CI->form_validation->set_rules('password', 'Contraseña', 'required', array('required' => '%s es requerida')); 
              //if($role=="admin"||$role=="admin")
                $this->CI->form_validation->set_rules('re_password', 'Confirmar contraseña', 'required|matches[password]', array('required' => '%s es requerida','matches'=>'%s no coincide con la contraseña')); 

        }
          
        if($role=="advertiser"){           
            $this->CI->form_validation->set_rules('company', 'Empresa', 'required', array('required' => '%s es requerida'));
            $this->CI->form_validation->set_rules('nit', 'Numero de nit', 'required', array('required' => '%s es requerido'));
         }

        if($role=="client"){           
            $this->CI->form_validation->set_rules('birthday', 'Fecha de Nacimiento', 'required', array('required' => '%s es requerida'));
         }         
        
        if ($this->CI->form_validation->run() == FALSE)
                return false;
        
        $this->CI->form_validation->reset_validation();
        return true;
    }
   /*
    * @method recovery_request
    * return bool
    */
    public function recovery_request(){
        $this->CI->form_validation->set_rules('email', 'Correo electronico', 'required|valid_email', array(
            'required' => '%s es requerido',
            'valid_email' => 'El %s debe ser valido',
                )
        );
        if ($this->CI->form_validation->run() == FALSE) {
            return false;
        }
        $this->CI->form_validation->reset_validation();
        return true;
    }
    
    /*
     * @method news_request
     * request for news form
     * @return bool
     */
    public function hotspot_request(){

        $this->CI->form_validation->set_rules('name', 'Nombre', 'required', array('required' => '%s es requerido',));
        $this->CI->form_validation->set_rules('location', 'Dirección de red', 'required', array( 'required' => '%s es requerida',));
        $this->CI->form_validation->set_rules('serial', 'Lista de MAC', 'required', array('required' => '%s es requerido'));
        $this->CI->form_validation->set_rules('area', 'Cubrimiento de area', 'required', array('required' => '%s es requerida'));
        $this->CI->form_validation->set_rules('day_amount', 'Valor pauta por día', 'required|integer', array('required' => '%s es requerido',"integer"=>"%s permite solo numeros enteros"));
        $this->CI->form_validation->set_rules('click_amount', 'Valor pauta click', 'required|integer', array('required' => '%s es requerido',"integer"=>"%s permite solo numeros enteros"));
        $this->CI->form_validation->set_rules('print_amount', 'Valor pauta visualización', 'required|integer', array('required' => '%s es requerido',"integer"=>"%s permite solo numeros enteros"));
        $this->CI->form_validation->set_rules('hardware', 'Dispositivo WIFI', 'required', array('required' => '%s es requerido'));

        if ($this->CI->form_validation->run() == FALSE) {
            return false;
        }
       $this->CI->form_validation->reset_validation();
        return true;
    }
    /*
     * @method campaign_request
     * request for campaigns
     * @return bool
     */
    public function campaign_request($level){
        if($level==3){
            $this->CI->form_validation->set_rules('name', 'Nombre', 'required', array('required' => '%s es requerido',));
            $this->CI->form_validation->set_rules('cap_type', 'Tipo de cobro', 'required', array( 'required' => '%s es requerido',));
            $this->CI->form_validation->set_rules('quantity', 'Cantidad tipo cobro', 'required|integer', array('required' => '%s es requerido','integer'=>'%s solo permite numeros enteros'));
            $this->CI->form_validation->set_rules('amount', 'Monto disponible', 'required|integer', array('required' => '%s es requerida','integer'=>'%s solo permite numeros enteros'));
            $this->CI->form_validation->set_rules('start_date', 'Fecha de inicio', 'required', array('required' => '%s es requerida'));
        }
        
        if($level<=2){
           $this->CI->form_validation->set_rules('duration', 'Segundos de duración', 'required|integer', array('required' => '%s es requerido','integer'=>'%s solo permite numeros enteros'));
        }
        if($level>1){
           $this->CI->form_validation->set_rules('filter1', 'Filtro de audiencia por genero', 'required', array('required' => '%s es requerido'));
           $this->CI->form_validation->set_rules('filter2[]', 'Filtro de audiencia por edad', 'required', array('required' => '%s es requerido'));
         }
        
        $this->CI->form_validation->set_rules('hotspot[]', 'Puntos de red', 'required', array('required' => '%s es requerido'));

        if ($this->CI->form_validation->run() == FALSE) {
            $this->isValid=false;
            return false;
        }
       $this->CI->form_validation->reset_validation();
        return true;
    }
    /*
     * @method campaign_request
     * request for campaigns
     * @return bool
     */
    public function message_request(){
        
        $this->CI->form_validation->set_rules('message', 'El mensaje', 'required', array('required' => '%s es requerido',));
        $this->CI->form_validation->set_rules('type_id', 'Tipo de mensaje', 'required', array( 'required' => '%s es requerido',));
//        $this->CI->form_validation->set_rules('start_date', 'Fecha de inicio', 'required', array('required' => '%s es requerida'));
//        $this->CI->form_validation->set_rules('finish_date', 'Fecha de fin', 'required', array('required' => '%s es requerida'));
       
        if ($this->CI->form_validation->run() == FALSE) {
            $this->isValid=false;
            return false;
        }
       $this->CI->form_validation->reset_validation();
        return true;
    }
    /*
     * @method getIsValidForm
     * return bool
     */
    public function getIsValidForm(){
        return $this->isValid;
    }
}