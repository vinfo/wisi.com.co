<?php
class Hotspot extends CI_Controller{
    
    public function __construct() {
        parent::__construct();
        $this->load->library('auth');
        $this->load->model('hotspot_model');
    }
    /*
     * @method hotspot
     * vista de la administracion de puntos de acceso
     */

    public function hotspot($id = "") {

            if ($this->session->type_id <= 2 && $this->session->status == 1) {

                $data['body'] = 'admin/hotspot';
                $data['title'] = 'Puntos de red';
                $data['devices'] = $this->wisi_model->GetTypesByGroup(3);

                if (!empty($id) && is_numeric($id)) {

                    if (isset($_POST['action']) && $_POST['action'] == "edit") {
                        $this->edit($id);
                    }//end action

                    $data['hotspotdata'] = $this->hotspot_model->GetHotSpotById($id);
                }//end id

                if (isset($_POST['action']) && $_POST['action'] == "new") {

                    $this->add();
                }//end new

                if (!empty($id) && !$data['hotspotdata'])
                    show_404();

                $this->load->view('admin/layout/base', $data);
            }else {
                show_404();
            }
        
    }

    /*
     * @method add
     * crear nuevo hotspot
     * @return void
     */

    private function add() {
        if ($this->request_validation->hotspot_request("new")) {
            $formValidation = true;
            
            if ($formValidation) {
                $prependUser = array(
                    'name'          => $this->input->post('name'),
                    'location'      => $this->input->post('location'),
                    'serial'        => $this->input->post('serial'),
                    'area'          => $this->input->post('area'),
                    'hardware'      => $this->input->post('hardware'),
                    'day_amount'    => $this->input->post('day_amount'),
                    'click_amount'  => $this->input->post('click_amount'),
                    'print_amount'  => $this->input->post('print_amount'),
                    'cdate'         => $this->config->item('current_date'),
                    'status'        => $this->input->post('status')
                );

                $insert = $this->hotspot_model->AddHotSpot($prependUser);

                if ($insert) {
                    $this->session->set_flashdata('success', 'El hotspot se creó satisfactoriamente');
                    //auditoria
                    $this->wisi_model->audit(array(
                        "date"      =>$this->config->item('current_date'),
                        "module"    =>"HOTSPOT",
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
     * ediccion de hotspot existente
     * @return void
     */

    private function edit($id) {
        if ($this->request_validation->hotspot_request("edit")) {
            $formValidation = true;
       
            if ($formValidation) {
                $prependHot = array(
                    'name'          => $this->input->post('name'),
                    'location'      => $this->input->post('location'),
                    'serial'        => $this->input->post('serial'),
                    'area'          => $this->input->post('area'),
                    'day_amount'    => $this->input->post('day_amount'),
                    'click_amount'  => $this->input->post('click_amount'),
                    'print_amount'  => $this->input->post('print_amount'),
                    'hardware'      => $this->input->post('hardware'),
                    'status'        => $this->input->post('status')
                );

                $update = $this->hotspot_model->UpdateHotSpot($id, $prependHot);

                if ($update) {
                    $this->session->set_flashdata('success', 'El hotspot se actualizo satisfactoriamente');
                    //auditoria
                    $this->wisi_model->audit(array(
                        "date"      =>$this->config->item('current_date'),
                        "module"    =>"HOTSPOT",
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