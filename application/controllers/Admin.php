<?php

class Admin extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('auth');
    }

    public function index() {
        $data['title'] = 'Panel Administrativo';
        $data['body'] = 'admin/index';

        $this->load->view('admin/layout/base', $data);
    }

    /* @method login
     * vista de login para ingresar al panela administrativo
     */

    public function login() {

        if (isset($_POST['action']) && $_POST['action'] == "dologin") {

            if ($this->request_validation->login_request()) {

                $login = $this->auth->login($this->input->post('email'), sha1($this->input->post('password')), 1);
                if ($login) {
                    //auditoria
                    $this->wisi_model->audit(array(
                        "date" => $this->config->item('current_date'),
                        "module" => "LOGIN",
                        "action" => "SELECT",
                        "sql" => $this->db->last_query(),
                        "user_id" => $this->session->id
                    ));
                    $this->session->set_userdata(array("last_seen" => $this->config->item('current_date')));
                    redirect('/');
                } else {
                    $this->session->set_flashdata('error', 'El usuario o la contraseña no son validos');
                }
            }
        }
        $data['title'] = 'Acceso Administrativo';
        $this->load->view('admin/login', $data);
    }

    /*
     * @method messages
     */

    public function message($id = "") {

        if ($this->session->status == 1 && ($this->session->type_id == 1 || $this->session->type_id == 2)) {

            $data['title'] = "Mensajes del sistema";
            $data['body'] = "admin/messages";
            $data['types'] = $this->wisi_model->GetTypesByGroup(5);

            if (!empty($id) && is_numeric($id)) {

                if (isset($_POST['action']) && $_POST['action'] == "edit") {
                    if ($this->request_validation->message_request("edit")) {
                        $image = null;
                        $formValidation = true;

                        if ($_FILES['image']['name'] != "") {

                            $imgTodelete = $this->wisi_model->GetMessageById($id);

                            if ($imgTodelete->image != null) {
                                unlink("./assets/media/message/" . $imgTodelete->image);
                                unlink("./assets/media/message/thumb/" . $imgTodelete->image);
                            }

                            $uploading['upload_path'] = "./assets/media/message";
                            $uploading['allowed_types'] = 'gif|jpg|png';
                            $uploading['encrypt_name'] = true;
                            $uploading['max_size'] = '2048';
                            $this->upload->initialize($uploading);
                            $this->upload->do_upload('image');
                            $file = $this->upload->data();
                            $image = $file['file_name'];

                            $crop['image_library'] = 'gd2';
                            $crop['source_image'] = './assets/media/message/' . $image;
                            $crop['new_image'] = './assets/media/user/message/' . $image;
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
                            $prependMessage = array(
                                'message' => $this->input->post('message'),
                                'image' => $image,
                                'start_date' => $this->input->post('start_date'),
                                'finish_date' => isset($_POST['finish_date']) ? $this->input->post('finish_date') : "",
                                'type_id' => $this->input->post('type_id'),
                            );

                            $update = $this->wisi_model->UpdateMessage($id, $prependMessage);

                            if ($update) {
                                $this->session->set_flashdata('success', 'El mensaje se actualizo satisfactoriamente');
                                //auditoria
                                $this->wisi_model->audit(array(
                                    "date" => $this->config->item('current_date'),
                                    "module" => "MENSAJES DE SISTEMA",
                                    "action" => "UPDATE",
                                    "sql" => $this->db->last_query(),
                                    "user_id" => $this->session->id
                                ));
                            } else {
                                $this->session->set_flashdata('error', 'Ha ocurrido un error inesperado, vuelve a intentarlo mas tarde');
                            }
                        }
                    }//end validation
                }//end action

                $data['messagedata'] = $this->wisi_model->GetMessageById($id);
            }//end id

            if (isset($_POST['action']) && $_POST['action'] == "new") {

                if ($this->request_validation->message_request()) {
                    $image = null;
                    $formValidation = true;
                    if ($_FILES['image']['name'] != "") {

                        $uploading['upload_path'] = "./assets/media/message";
                        $uploading['allowed_types'] = 'gif|jpg|png';
                        $uploading['encrypt_name'] = true;
                        $uploading['max_size'] = '2048';
                        $this->upload->initialize($uploading);
                        $this->upload->do_upload('image');
                        $file = $this->upload->data();
                        $image = $file['file_name'];

                        $crop['image_library'] = 'gd2';
                        $crop['source_image'] = './assets/media/message/' . $image;
                        $crop['new_image'] = './assets/media/message/thumb/' . $image;
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
                        $prependMessage = array(
                            'message' => $this->input->post('message'),
                            'image' => $image,
                            'start_date' => $this->input->post('start_date'),
                            'finish_date' => isset($_POST['finish_date']) ? $this->input->post('finish_date') : "",
                            'type_id' => $this->input->post('type_id'),
                            'cdate' => $this->config->item('current_date')
                        );

                        $insert = $this->wisi_model->AddMessage($prependMessage);

                        if ($insert) {
                            $this->session->set_flashdata('success', 'El mensaje se creó satisfactoriamente');
                            //auditoria
                            $this->wisi_model->audit(array(
                                "date" => $this->config->item('current_date'),
                                "module" => "MENSAJES DE SISTEMA",
                                "action" => "INSERT",
                                "sql" => $this->db->last_query(),
                                "user_id" => $this->session->id
                            ));
                        } else {
                            $this->session->set_flashdata('error', 'Ha ocurrido un error inesperado, vuelve a intentarlo mas tarde');
                        }
                    }
                }//end validation
            }//end new

            if (!empty($id) && !$data['messagedata'])
                show_404();

            $this->load->view('admin/layout/base', $data);
        }else {
            show_404();
        }
    }
    /*
     * @method recovery
     * recuperar contraseña
     */
    public function recovery(){
        $data['title']="Recuperar contraseña";
        if(isset($_POST['action'])&&$_POST['action']=="recovery"){
            
         if ($this->request_validation->recovery_request()) {
                $email=$this->input->post('email');
             
                    if($this->auth->recovery($email)){
                        
                            $this->session->set_flashdata('success', 'Se ha enviado un correo a la siguiente direccion "'.$email.'" con la nueva contraseña');
                    }else{
                            $this->session->set_flashdata('error', 'El correo "'.$email.'" no se encuentra asociado a ninguna cuenta');
                    }
                }
         }
       
        $this->load->view("admin/recovery",$data);
        }
    /*
     * @method logout
     * salir del panel administrativo
     */

    public function logout() {
        $this->auth->logout();
        redirect("/");
    }

}