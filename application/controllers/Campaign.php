<?php
class Campaign extends CI_Controller{
    
    protected $level=array('global','connection','action');
    
    public function __construct() {
        parent::__construct();
        $this->load->library('auth');
        $this->load->model("campaign_model");
        $this->load->model('hotspot_model');
    }
    
    
    public function index($level="",$id = "") {
        
        if(!in_array($level, $this->level))
            show_404 ();
        
        $data['title'] = "Campañas";
        $data['body'] = "admin/campaign";
        $data['campaigns'] = $this->wisi_model->GetTypesByGroup(4);
        $data['hotspots'] = $this->hotspot_model->GetHotSpots();
        $data['filters'] =$this->wisi_model->GetTypesByGroup(6);
        
        switch($level){
            case'global':
                $data['level']=1;
                break;
            case'connection':
                $data['level']=2;
                break;
            case'action':
                $data['level']=3;
                break;
        }

        if (isset($_POST['action']) && $_POST['action'] == "new") {

            $this->add($data['level']);
        }//end new

        if (!empty($id) && is_numeric($id)) {
            if (isset($_POST['action']) && $_POST['action'] == "edit") {

                $this->edit($id,$data['level']);
            }//end action
            $data['campigndata'] = $this->campaign_model->GetCampaignById($id);
            $checkHots = array();
            $checkFilters=array();

            if ($data['campigndata']) {

                foreach ($this->campaign_model->GetHostSpotsOnCampaign($id) as $hotspot) {
                    $checkHots[$hotspot->hotspot_id] = true;
                }
                 foreach ($this->campaign_model->GetFiltersOnCampaign($id) as $filter) {
                    $checkFilters[$filter->type_id] = true;
                }
                $data['spots'] = $checkHots;
                $data['filter'] = $checkFilters;

                $data['editing'] = true;
                if ($data['campigndata']->campaing_type == 34) {
                    $data['answers'] = $this->campaign_model->GetAnswersSurvey($id);
                }
            }
            
        }//end id
        if (!empty($id) && !$data['campigndata'])
            redirect($this->uri->segment(1)."/".$this->uri->segment(2));

        $this->load->view('admin/layout/base', $data);
    }
    

    private function add($level){
        if ($this->request_validation->campaign_request($level)) {
            $image = null;
            $formValidation = true;
            if ($_FILES['image']['name'] != "") {

                $uploading['upload_path'] = "./assets/media/campaign";
                $uploading['allowed_types'] = 'gif|jpg|png';
                $uploading['encrypt_name'] = true;
                $uploading['max_size'] = '2048';
                $this->upload->initialize($uploading);
                $this->upload->do_upload('image');
                $file = $this->upload->data();
                $image = $file['file_name'];

                $crop['image_library'] = 'gd2';
                $crop['source_image'] = './assets/media/campaign/' . $image;
                $crop['new_image'] = './assets/media/campaign/thumb/' . $image;
                $crop['maintain_ratio'] = TRUE;
                $crop['width'] = 300;
                $crop['height'] = 300;

                $this->load->library('image_lib', $crop);
                $this->image_lib->resize();

                if (!$file['is_image']) {
                    $this->session->set_flashdata('error', 'El archivo no pudo ser cargado correctamente verifique el archivo y vuelva a cargar');
                    $formValidation = false;
                }
            }
            if ($formValidation) {
              
                $prependCampaign = array(
                    'name'              => isset($_POST['name']) ?$this->input->post('name'):"",
                    'start_date'        => isset($_POST['start_date']) ?$this->input->post('start_date'):"",
                    'finish_date'       => isset($_POST['finish_date']) ? $this->input->post('finish_date') : "",
                    'media'             => isset($_POST['media']) ? $this->input->post('media') : "",
                    'question'          => isset($_POST['question']) ? $this->input->post('question') : "",
                    'description'       => isset($_POST['description']) ? $this->input->post('description') : "",
                    'description_media' => isset($_POST['description_media']) ? $this->input->post('description_media') : "",
                    'quantity'          => isset($_POST['quantity']) ?$this->input->post('quantity'):0,
                    'amount'            => isset($_POST['amount']) ?$this->input->post('amount'):0,
                    'duration'          => isset($_POST['duration']) ?$this->input->post('duration'):0,
                    'image'             => $image,
                    'cap_type'          => isset($_POST['cap_type']) ?$this->input->post('cap_type'):0,
                    'campaing_type'     => isset($_POST['cap_type']) ?$this->input->post('campaing_type'):37,
                    'level'             =>$this->input->post('level'),
                    'status'            => isset($_POST['status']) ?$this->input->post('status'):0,
                );

                $insert = $this->campaign_model->AddCampaign($prependCampaign);
                
               

                if ($insert) {
                    $this->session->set_flashdata('success', 'La campaña se creó satisfactoriamente');
                    $lascampaign=$this->db->insert_id();
                    
                    
                    
                    //auditoria
                    $this->wisi_model->audit(array(
                        "date"      =>$this->config->item('current_date'),
                        "module"    =>"CAMPAÑA",
                        "action"    =>"INSERT",
                        "sql"       =>$this->db->last_query(),
                        "user_id"   =>$this->session->id
                    ));
                    
                    //agrego relaciones
                    $this->addRelations($lascampaign,'new');
                    
                } else {
                    $this->session->set_flashdata('error', 'Ha ocurrido un error inesperado, vuelve a intentarlo mas tarde');
                }
            }
        }//end validation
    }
    
    private function edit($id,$level){
        if ($this->request_validation->campaign_request($level)) {
            $image = null;
            $formValidation = true;
            if (isset($_FILES['image']['name'])&&$_FILES['image']['name'] != "") {
                
                $imgTodelete = $this->campaign_model->GetCampaignById($id);

                if ($imgTodelete->image != null) {
                    unlink("./assets/media/campaign/" . $imgTodelete->image);
                    unlink("./assets/media/campaign/thumb/" . $imgTodelete->image);
                }

                $uploading['upload_path'] = "./assets/media/campaign";
                $uploading['allowed_types'] = 'gif|jpg|png';
                $uploading['encrypt_name'] = true;
                $uploading['max_size'] = '2048';
                $this->upload->initialize($uploading);
                $this->upload->do_upload('image');
                $file = $this->upload->data();
                $image = $file['file_name'];

                $crop['image_library'] = 'gd2';
                $crop['source_image'] = './assets/media/campaign/' . $image;
                $crop['new_image'] = './assets/media/campaign/thumb/' . $image;
                $crop['maintain_ratio'] = TRUE;
                $crop['width'] = 300;
                $crop['height'] = 300;

                $this->load->library('image_lib', $crop);
                $this->image_lib->resize();

                if (!$file['is_image']) {
                    $this->session->set_flashdata('error', 'El archivo no pudo ser cargado correctamente verifique el archivo y vuelva a cargar');
                    $formValidation = false;
                }
            }
           
            if ($formValidation) {
              
                $prependCampaign = array(
                    'name'              => isset($_POST['name']) ?$this->input->post('name'):"",
                    'start_date'        => isset($_POST['start_date']) ?$this->input->post('start_date'):"",
                    'finish_date'       => isset($_POST['finish_date']) ? $this->input->post('finish_date') : "",
                    'media'             => isset($_POST['media']) ? $this->input->post('media') : "",
                    'question'          => isset($_POST['question']) ? $this->input->post('question') : "",
                    'description'       => isset($_POST['description']) ? $this->input->post('description') : "",
                    'description_media' => isset($_POST['description_media']) ? $this->input->post('description_media') : "",
                    'quantity'          => isset($_POST['quantity']) ?$this->input->post('quantity'):0,
                    'amount'            => isset($_POST['amount']) ?$this->input->post('amount'):0,
                    'duration'          => isset($_POST['duration']) ?$this->input->post('duration'):0,
                    'cap_type'          => isset($_POST['cap_type']) ?$this->input->post('cap_type'):0,
                    'campaing_type'     => isset($_POST['campaing_type']) ?$this->input->post('campaing_type'):37,
                    'level'             => $this->input->post('level'),
                    'status'            => isset($_POST['status']) ?$this->input->post('status'):0,
                );

                 if ($image != null)
                    $prependCampaign["image"] = $image;
                 
                
                
                $update = $this->campaign_model->UpdateCampaign($id,$prependCampaign);
                
               

                if ($update) {
                    $this->session->set_flashdata('success', 'La campaña se actualizó satisfactoriamente');
                    $lascampaign=$this->db->insert_id();
                    
                    //auditoria
                    $this->wisi_model->audit(array(
                        "date"      =>$this->config->item('current_date'),
                        "module"    =>"CAMPAÑA",
                        "action"    =>"UPDATE",
                        "sql"       =>$this->db->last_query(),
                        "user_id"   =>$this->session->id
                    ));
                    //borro relaciones 
                    $this->campaign_model->DeleteRelations($id);
                    
                    //agrego relaciones
                    $this->addRelations($id,'edit');
                    
                } else {
                    $this->session->set_flashdata('error', 'Ha ocurrido un error inesperado, vuelve a intentarlo mas tarde');
                }
            }
        }//end validation
    }
    /*
     * @method addRelations
     * @param campaign_id
     * @void
     */
    private function addRelations($campaign_id,$action) {
        if($action=="new"){
            $insertRelatedUser = $this->campaign_model->AddCampaignUser(array(
                "campaign_id" => $campaign_id,
                "user_id" => $this->session->id,
            ));
        }
        
        if (isset($_POST['filter1']) && isset($_POST['filter2'])) {

                $insertRelatedFilter = $this->campaign_model->AddCampaignFilters(array(
                    "campaign_id" => $campaign_id,
                    "type_id" => $this->input->post('filter1'),
                ));
                foreach ($this->input->post('filter2') as $filter2) {
                   $insertRelatedFilter = $this->campaign_model->AddCampaignFilters(array(
                       "campaign_id" => $campaign_id,
                       "type_id" => $filter2,
                   ));
                }
        }
        
        if (isset($_POST['hotspot']) && !empty($_POST['hotspot'])) {

            foreach ($this->input->post('hotspot') as $spot) {

                $insertRelatedHotspot = $this->campaign_model->AddCampaignHotspot(array(
                    "campaign_id" => $campaign_id,
                    "hotspot_id" => $spot,
                ));
            }
        }
        if ($this->input->post('campaing_type') == 34) {

            if (isset($_POST['answer']) && !empty($_POST['answer'])) {

                foreach ($this->input->post('answer') as $answer) {

                    $insertRelatedHotspot = $this->campaign_model->AddAnswerToSurvey(array(
                        "campaign_id" => $campaign_id,
                        "answer" => $answer,
                    ));
                }
            }
        }
    }
    /*
     * @method my_campaign
     * vistade las campañas del usuario
     */
    public function my_campaign(){
        
        if($this->session->type_id<>4)
            show_404 ();
        
        $data['title']="Mis camapañas";
        $data['body']="admin/my_campaign";
        $this->load->view("admin/layout/base",$data);
    }

}

