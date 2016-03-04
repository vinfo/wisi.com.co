<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Serve extends CI_Controller{
    
    public function __construct() {
        parent::__construct();
        header('Content-Type: application/json');
    }
    
    /*
     * @method recovery
     * recuperar la contraseña por medio del correo
     */  
     public function recovery(){
        
        if(isset($_POST['action'])&&$_POST['action']='recovery'){
                   
           if($this->auth->recovery($this->input->post('email'),1)) {
                 echo json_encode(array("status"=>"ok"));
            }else{
                 echo json_encode(array("status"=>"fail"));
            }
           
        }else{
            echo json_encode(array("status"=>"fail","msg"=>"not allowed"));
        }
    }
    /*
     * @method getCities
     * obtener las ciudades segun su pais
     */
    public function getCities(){
        $country=$this->input->get('country');
        $cities=$this->wisi_model->GeCitiesByCountryCode($country);
        if($cities){
            echo json_encode(array("status"=>"ok","data"=>$cities));
        }else{
             echo json_encode(array("status"=>"fail"));
        }
    }
    /*
     * @method addImagesGallery
     * agregar imagenes de una galeria 
     */
    public function addImagesGallery(){
        if(isset($_POST['action'])&&$_POST['action']=="addImg"){
            $gallery =$this->input->post('id_gallery');
            
            $img_big_width =$this->input->post('big_width');
            $img_big_height=$this->input->post('big_height');
            $img_medium_width =$this->input->post('medium_width');
            $img_medium_height =$this->input->post('medium_height');
            $img_small_width =$this->input->post('small_width');
            $img_small_height=$this->input->post('small_height');
            
                  if($_FILES['file']['name']!=""){
                              
                            $uploading['upload_path'] =  "./assets/media/gallery/".$gallery."/big";
                            $uploading['allowed_types'] = 'gif|jpg|png';
                            $uploading['encrypt_name']=true;
                            $uploading['max_size']  = '2048';
                            $this->upload->initialize($uploading);
                            $this->upload->do_upload('file');
                            $file=$this->upload->data();
                            $image=$file['file_name'];
                            
                            $this->load->library('image_lib');
                            
                            $big['image_library'] = 'gd2';
                            $big['source_image'] = "./assets/media/gallery/".$gallery."/big/".$image;
                            $big['new_image'] = "./assets/media/gallery/".$gallery."/big/".$image;
                            $big['maintain_ratio'] = TRUE;
                            $big['width']= $img_big_width;
                            $big['height'] = $img_big_height;
                            $this->image_lib->initialize($big);
                            $this->image_lib->resize();
                            $this->image_lib->clear();
                            
                            $medium['image_library'] = 'gd2';
                            $medium['source_image'] = "./assets/media/gallery/".$gallery."/big/".$image;
                            $medium['new_image'] = "./assets/media/gallery/".$gallery."/medium/".$image;
                            $medium['maintain_ratio'] = TRUE;
                            $medium['width']= $img_medium_width;
                            $medium['height'] = $img_medium_height;
                            $this->image_lib->initialize($medium);
                            $this->image_lib->resize();
                            $this->image_lib->clear();
                            
                            $small['image_library'] = 'gd2';
                            $small['source_image'] = "./assets/media/gallery/".$gallery."/big/".$image;
                            $small['new_image'] = "./assets/media/gallery/".$gallery."/small/".$image;
                            $small['maintain_ratio'] = TRUE;
                            $small['width']= $img_small_width;
                            $small['height'] = $img_small_height;
                            $this->image_lib->initialize($small);
                            $this->image_lib->resize();
                            $this->image_lib->clear();
                            
                            $old = umask(0); 
                            chmod("./assets/media/gallery/".$gallery."/big/".$image, 0777);
                            chmod("./assets/media/gallery/".$gallery."/medium/".$image, 0777);
                            chmod("./assets/media/gallery/".$gallery."/small/".$image, 0777);
                            umask($old); 
                            
                            $prepednItems =array(
                                "title"=>$_FILES['file']['name'],
                                "path"=>$image,
                                "gallery_id"=>$gallery
                            );
                            $insert=$this->unlab_model->AddItemsGallery($prepednItems);
                  }
        }
    }
    
    /*
     *@method deleteItems
     * por item del datatable por su id y su tabla a la que pertenece 
     */
    public function deleteItems(){
         if(isset($_POST['action'])&&$_POST['action']='deleteItem'){
            
             $delItem=true;
             foreach ($this->input->post('data') as $item) {
                 $delItem=$this->wisi_model->DeleteSingleRowById($this->input->post('table'),$item);
                 //auditoria
                    $this->wisi_model->audit(array(
                        "date"      =>$this->config->item('current_date'),
                        "module"    =>"N/A",
                        "action"    =>"DELETE",
                        "sql"       =>$this->db->last_query(),
                        "user_id"   =>$this->session->id
                    ));
             }
             if($delItem){
                 echo json_encode(array("status"=>"ok","msg"=>"ok"));
             }else{
                 echo json_encode(array("status"=>"fail","msg"=>"fail"));
             }
             
        }else{
            echo json_encode(array("status"=>"fail","msg"=>"not allowed"));
        }
    }
}