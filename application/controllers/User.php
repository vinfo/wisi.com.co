<?php

class User extends CI_Controller {

    protected $type_user = array('super','admin', 'client', 'advertiser','profile');

    public function __construct() {
        parent::__construct();
        $this->load->library('auth');
        $this->load->model('user_model');
    }

    /*
     * @method user
     * vista de la administracion de usuarios
     */

    public function user($type = "", $id = "") {

            if ($this->session->status == 1 &&$this->session->type_id <=2 && $type != "" && in_array($type, $this->type_user)) {
                $validateRol = "admin";
                switch ($type) {
                    case "admin":
                        $data['title'] = "Usuarios administrativos";
                        $data['realType'] = 1;
                        $validateRol = "admin";
                        $data['body'] = 'admin/user_admin';
                        break;
                    case "client":
                        $data['title'] = "Clientes";
                        $data['realType'] = 3;
                        $validateRol = "client";
                        $data['body'] = 'admin/user_client';
                        break;
                    case "advertiser":
                       $data['title'] = "Usuarios Anunciante";
                       $data['realType'] = 4;
                       $validateRol = "advertiser";
                       $data['body'] = 'admin/user_advertiser';
                       break;
                 }
                
                $data['roles'] = $this->wisi_model->GetTypesByGroup(1);
                $data['countries'] = $this->wisi_model->GetCountries();
                $data['marital'] = $this->wisi_model->GetTypesByGroup(2);

                
                if (!empty($id) && is_numeric($id)) {

                    if (isset($_POST['action']) && $_POST['action'] == "edit") {

                        $this->edit($id,$validateRol);
                    }//end action
                    $data['userdata'] = $this->user_model->GetUserById($id);
                    $data['cities'] = $this->wisi_model->GeCitiesByCountryCode($data['userdata']->country);
                }//end id
                

                if (isset($_POST['action']) && $_POST['action'] == "new") {

                    $this->add($validateRol);
                }//end new

                if (!empty($id) && !$data['userdata'])
                    show_404();

                $this->load->view('admin/layout/base', $data);
            }else {
                show_404();
            }
        
    }
    
    /*
     * @method profile
     * obtener datos de perfil y vista
     */
    public function profile(){
        
        $data['title'] = 'Mi perfil';
        $data['body'] = 'admin/user';
        $data['userdata'] = $this->user_model->GetUserById($this->session->id);
        $data['countries'] = $this->wisi_model->GetCountries();
        $data['cities'] = $this->wisi_model->GeCitiesByCountryCode($data['userdata']->country);
        $data['marital'] = $this->wisi_model->GetTypesByGroup(2);
        $data['roles'] = $this->wisi_model->GetTypesByGroup(1);
        $data['realType']=$this->session->type_id;
        $data['avoidDataTable']=true;
        
        if (isset($_POST['action']) && $_POST['action'] == "edit") {
            $validateRold = "";
            switch ($this->session->type_id) {
                case "admin":
                    $validateRol = "admin";
                    break;
                case "client":
                    $validateRol = "client";
                    break;
                case "advertiser":
                    $validateRol = "advertiser";
                    break;
            }

            $this->edit($this->session->id, $validateRold);
        }//end action
        $this->load->view('admin/layout/base', $data);
    }

    /*
     * @method add
     * @param role for validation fields
     * agregar usuario nuevo
     */

    private function add($role) {
        
        if ($this->request_validation->user_request("new", $role)) {
            $image = null;
            $formValidation = true;
            if ($_FILES['image']['name'] != "") {

                $uploading['upload_path'] = "./assets/media/user";
                $uploading['allowed_types'] = 'gif|jpg|png';
                $uploading['encrypt_name'] = true;
                $uploading['max_size'] = '2048';
                $this->upload->initialize($uploading);
                $this->upload->do_upload('image');
                $file = $this->upload->data();
                $image = $file['file_name'];

                $crop['image_library'] = 'gd2';
                $crop['source_image'] = './assets/media/user/' . $image;
                $crop['new_image'] = './assets/media/user/thumb/' . $image;
                $crop['maintain_ratio'] = TRUE;
                $crop['width'] = 120;
                $crop['height'] = 80;

                $this->load->library('image_lib', $crop);
                $this->image_lib->resize();

                if (!$file['is_image']) {
                    $this->session->set_flashdata('error', 'El archivo no pudo ser cargado correctamente verifique el archivo y vuelva a cargar');
                    $formValidation = false;
                }
            }
            if ($formValidation) {
              
                $prependUser = array(
                    'name'          => $this->input->post('name'),
                    'lastname'      => $this->input->post('lastname'),
                    'email'         => $this->input->post('email'),
                    'password'      => sha1($this->input->post('password')),
                    'image'         => $image,
                    'genre'         => isset($_POST['genre']) ? $this->input->post('genre') : "",
                    'phone'         => isset($_POST['phone']) ? $this->input->post('phone') : "",
                    'celphone'      => isset($_POST['celphone']) ? $this->input->post('celphone') : "",
                    'country'       => isset($_POST['country']) ? $this->input->post('country') : "",
                    'city'          => isset($_POST['city']) ? $this->input->post('city') : "",
                    'marital'       => isset($_POST['marital']) ? $this->input->post('marital') : "",
                    'birthday'      => isset($_POST['birthday']) ? $this->input->post('birthday') : "",
                    'address'      => isset($_POST['address']) ? $this->input->post('address') : "",
                    'company'       => isset($_POST['company']) ? $this->input->post('company') : "",
                    'nit'           => isset($_POST['nit']) ? $this->input->post('nit') : "",
                    'newsletter'    => isset($_POST['newsletter']) ? $this->input->post('newsletter') : 0,
                    'type_id'       => $this->input->post('type_id'),
                    'cdate'         => $this->config->item('current_date'),
                    'status'        => $this->input->post('status'),
                );

                $insert = $this->user_model->AddUser($prependUser);

                if ($insert) {
                    $this->session->set_flashdata('success', 'El usuario se creó satisfactoriamente');
                    //auditoria
                    $this->wisi_model->audit(array(
                        "date"      =>$this->config->item('current_date'),
                        "module"    =>"USUARIOS",
                        "action"    =>"INSERT",
                        "sql"       =>$this->db->last_query(),
                        "user_id"   =>$this->session->id
                    ));
                } else {
                    $this->session->set_flashdata('error', 'Ha ocurrido un error inesperado, vuelve a intentarlo mas tarde');
                }
            }
        }//end validation
    }

    /*
     * @method edit
     * @param id
     * @param type role for database
     * @param role string for validate
     * editar el usuario
     */

    private function edit($id, $role) {

        if ($this->request_validation->user_request("edit", $role)){
            $image = null;
            $formValidation = true;
            
            if ($_FILES['image']['name'] != "") {

                $imgTodelete = $this->user_model->GetUserById($id);

                if ($imgTodelete->image != null) {
                    unlink("./assets/media/user/" . $imgTodelete->image);
                    unlink("./assets/media/user/thumb/" . $imgTodelete->image);
                }

                $uploading['upload_path'] = "./assets/media/user";
                $uploading['allowed_types'] = 'gif|jpg|png';
                $uploading['encrypt_name'] = true;
                $uploading['max_size'] = '2048';
                $this->upload->initialize($uploading);
                $this->upload->do_upload('image');
                $file = $this->upload->data();
                $image = $file['file_name'];

                $crop['image_library'] = 'gd2';
                $crop['source_image'] = './assets/media/user/' . $image;
                $crop['new_image'] = './assets/media/user/thumb/' . $image;
                $crop['maintain_ratio'] = TRUE;
                $crop['width'] = 150;
                $crop['height'] = 150;


                $this->load->library('image_lib', $crop);
                $this->image_lib->resize();

                if (!$file['is_image']) {
                    $this->session->set_flashdata('error', 'El archivo no pudo ser cargado correctamente verifique el archivo y vuelva a cargar');
                    $formValidation = false;
                }
            }
            if ($formValidation) {
                
                $prependUser = array(
                    'name'          =>   $this->input->post('name'),
                    'lastname'      =>   $this->input->post('lastname'),
                    'email'         =>   $this->input->post('email'),
                    'password'      =>   ($this->input->post('password') != "") ? sha1($this->input->post('password')) : $this->input->post('hpass'),
                    'genre'         =>   isset($_POST['genre']) ? $this->input->post('genre') : "",
                    'phone'         =>   isset($_POST['phone']) ? $this->input->post('phone') : "",
                    'celphone'      =>   isset($_POST['celphone']) ? $this->input->post('celphone') : "",
                    'country'       =>   isset($_POST['country']) ? $this->input->post('country') : "",
                    'city'          =>   isset($_POST['city']) ? $this->input->post('city') : "",
                    'marital'       =>   isset($_POST['marital']) ? $this->input->post('marital') : "",
                    'birthday'      =>   isset($_POST['birthday']) ? str_replace("/", "-", $this->input->post('birthday')) : "",
                    'username'      =>   isset($_POST['address']) ? $this->input->post('address') : "",
                    'company'       =>   isset($_POST['company']) ? $this->input->post('company') : "",
                    'nit'           =>   isset($_POST['nit']) ? $this->input->post('nit') : "",
                    'newsletter'    =>   isset($_POST['newsletter']) ? $this->input->post('newsletter') : 0,
                    'type_id'       =>   $this->input->post('type_id'),
                    'status'        =>   $this->input->post('status'),
                );

                if ($image != null)
                    $prependUser["image"] = $image;


                $update = $this->user_model->UpdateUser($id, $prependUser);

                if ($update) {
                    $this->session->set_flashdata('success', 'El usuario se actualizo satisfactoriamente');
                    //auditoria
                    $this->wisi_model->audit(array(
                        "date"      =>$this->config->item('current_date'),
                        "module"    =>"USUARIOS",
                        "action"    =>"UPDATE",
                        "sql"       =>$this->db->last_query(),
                        "user_id"   =>$this->session->id
                    ));
                } else {
                    $this->session->set_flashdata('error', 'Ha ocurrido un error inesperado, vuelve a intentarlo mas tarde');
                }
            }
        }//end validation
    }

}