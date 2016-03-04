<?php
defined('BASEPATH') OR exit('No direct script access allowed');

header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

require APPPATH . '/libraries/REST_Controller.php';

class Api extends REST_Controller {
    /*
     * restful para autenticación de usuario
     */

    public function auth_post() {
        $this->load->helper('string');

        $email = $this->post('email');
        $password = sha1($this->post('password'));

        if ($email != null && $password != null) {

            $query = $this->db->select('u.*')
                    ->from('user AS u')
                    ->where('u.password', $password)
                    ->where('u.status', 1)
                    ->where('u.email', $email)
                    ->join('type AS t', 't.id=u.type_id', 'INNER')
                    ->group_by('u.id')
                    ->get('user');

            if ($query->num_rows() > 0) {
                $auth = $query->row();
                $generic = random_string('alnum', 30);
                $tokenData = array("token" => $generic, "cdate" => $this->config->item('current_date'));
                $generateToken = $this->wisi_model->AddToken($tokenData);

                if ($generateToken) {
                    $this->response(array("userdata" => $auth, "token" => $tokenData), REST_Controller::HTTP_OK);
                }
            } else {
                $this->response(array(
                    'status' => FALSE,
                    'message' => 'access denied'
                        ), REST_Controller::HTTP_UNAUTHORIZED);
            }
        }
    }
    /*
     * autenticarce con las redes sociales desde la aplicacion movil
     */
    public function socialnetwork_post(){
        $this->load->helper('string');
        $auth = $this->post('data');
        if (is_array($auth) && !empty($auth)) 
        {
           
            $prependUser = array(
                'network' => $auth['id'],
                'name' => $auth['first_name'],
                'image' => $auth['picture']['data']['url'],
                'lastname' => $auth['last_name'],
                'email' => isset($auth['email'])?$auth['email']:"",
                'cdate'=>$this->config->item('current_date'),
                'type_id' => 3,
                'status' => 1,
            );
             $addUser=$this->wisi_model->AddSocialNetworkUser($prependUser);
             
           
            if ($addUser) {
                $generic =random_string('alnum', 30);
                $tokenData=array("token"=>$generic,"cdate"=>$this->config->item('current_date'));
                $generateToken =$this->wisi_model->AddToken($tokenData);
                
                if($generateToken){
                    
                     $this->response(array("userdata"=>$addUser,"token"=>$tokenData), REST_Controller::HTTP_OK);
                }
            } else {
                $this->response(array('status' => FALSE,
                                'message' => 'Forbidden'
                                    ),REST_Controller::HTTP_FORBIDDEN);
            } /* end add user */
        }
    }
    /*
     * @method register
     * @params obj
     * registrar usuario
     */
    public function register_post(){
        $this->load->helper('string');
        $this->load->model('user_model');
        $userdata = $this->post('data');
      
        if (is_array($userdata) && !empty($userdata)) 
        {
                $prependUser = array(
                    'name'          => $userdata['name'],
                    'lastname'      => $userdata['lastname'],
                    'email'         => $userdata['email_register'],
                    'password'      => sha1($userdata['password_register']),
                    'genre'         => $userdata['genre'],
                    'phone'         => isset($userdata['phone']) ? $userdata['phone'] : 0,
                    'celphone'      => isset($userdata['celphone']) ? $userdata['celphone'] : 0,
                    'country'       => isset($userdata['country']) ? $userdata['city'] : 0,
                    'city'          => isset($userdata['city']) ? $userdata['city'] : 0,
                    'marital'       => isset($userdata['marital']) ? $userdata['marital'] : 0,
                    'birthday'      => isset($userdata['birthday']) ? $userdata['birthday'] : "",
                    'address'      => isset($userdata['address']) ? $userdata['address'] : null,
                    'company'       => isset($userdata['company']) ? $userdata['company'] : null,
                    'nit'           => isset($userdata['nit']) ? $userdata['nit'] : null,
                    'newsletter'    => $userdata['newsletter'],
                    'type_id'       => $userdata['type_id'],
                    'cdate'         => $this->config->item('current_date'),
                    'status'        => 1,
                );
               
                /* validar correo existente*/
                if(!$this->user_model->checkUserEmail($userdata['email_register'],$userdata['type_id'])){
                   
                    
                    $insert = $this->user_model->AddUser($prependUser);
                    if($insert){
                        $generic =random_string('alnum', 30);
                        $redirect =false;
                        
                        if($userdata['type_id']==4){
                            $redirect=true;
                             $this->session->set_userdata(array(
                                 'id'=>$this->db->insert_id(),
                                 'name'=>$userdata['name'],
                                 'email'         => $userdata['email_register'],
                                 'genre'         => $userdata['genre'],
                                 'phone'         => isset($userdata['phone']) ? $userdata['phone'] : 0,
                                 'celphone'      => isset($userdata['celphone']) ? $userdata['celphone'] : 0,
                                 'country'       => isset($userdata['country']) ? $userdata['city'] : 0,
                                 'city'          => isset($userdata['city']) ? $userdata['city'] : 0,
                                 'marital'       => isset($userdata['marital']) ? $userdata['marital'] : 0,
                                 'birthday'      => isset($userdata['birthday']) ? $userdata['birthday'] : "",
                                 'username'      => isset($userdata['username']) ? $userdata['username'] : null,
                                 'company'       => isset($userdata['company']) ? $userdata['company'] : null,
                                 'nit'           => isset($userdata['nit']) ? $userdata['nit'] : null,
                                 'newsletter'    => $userdata['newsletter'],
                                 'type_id'       => $userdata['type_id'],
                                 'cdate'         => $this->config->item('current_date'),
                                 'status'        => 1,
                             ));
                        }
                        
                        $tokenData=array("token"=>$generic,"cdate"=>$this->config->item('current_date'));
                        $generateToken =$this->wisi_model->AddToken($tokenData);

                        if($generateToken){
                            $this->response(array("userdata"=>$prependUser,"token"=>$tokenData,"redirect"=>$redirect), REST_Controller::HTTP_OK);
                        }else{
                            $this->response(array('status' => FALSE,'message' => 'Forbidden'),REST_Controller::HTTP_FORBIDDEN);
                        }
                    }else{
                        $this->response(array('status' => FALSE,'message' => 'Forbidden'),REST_Controller::HTTP_FORBIDDEN);
                    }
            }else{
                $this->response(array('status' => FALSE,'message' => 'Forbidden'),REST_Controller::HTTP_UNAUTHORIZED);
            }
        }
        
    }
    
    /*
     * @method countries
     * obtener los paises
     */
    public function countries_get() {
        $countries = $this->wisi_model->GetCountries();
        if ($countries) 
        {
            $this->response($countries, REST_Controller::HTTP_OK);
        } else 
        {
            $this->response(array('status' => FALSE,'message' => 'Forbidden'), REST_Controller::HTTP_FORBIDDEN);
        }
    }
    /*
     * @method cities
     * @param code
     * obtiene las ciudades por su codgo de paise
     */
    public function cities_get(){
        $cities = $this->wisi_model->GeCitiesByCountryCode($this->input->get('code'));
        if ($cities) 
        {
            $this->response($cities, REST_Controller::HTTP_OK);
        } else 
        {
            $this->response(array('status' => FALSE,'message' => 'Forbidden'), REST_Controller::HTTP_FORBIDDEN);
        }
    }
    /*
     * @method arital
     * obtiene el estado civil
     */
    public function  marital_get(){
        $marital = $this->wisi_model->GetTypesByGroup(2);
        if ($marital) 
        {
            $this->response($marital, REST_Controller::HTTP_OK);
        } else 
        {
            $this->response(array('status' => FALSE,'message' => 'Forbidden'), REST_Controller::HTTP_FORBIDDEN);
        }
    }

}
