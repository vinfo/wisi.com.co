<?php
Class Wisi_model extends CI_Model{
    
    public function __construct() {
        parent::__construct();
    }
    
     /*
     * @method GetTypes
     * @return resultset Object result
     */
    public function GetTypesByGroup($groupId){
        $query =$this->db->where('group_id',$groupId)->get('type');
        if ($query->num_rows()>0) {
            return $query->result();
        }else{
            return false;
        }
    }
     /*
     * @method GetCountries
     * @return resultset Object result
     */
    public function GetCountries(){
        $query =$this->db->get('country');
        if ($query->num_rows()>0) {
            return $query->result();
        }else{
            return false;
        }
    }
    
     /*
     * @method GeCitiesByCountryId
     * @return resultset Object result
     */
    public function GeCitiesByCountryCode($country){
        $query =$this->db->where('country',$country)->get('city');
        if ($query->num_rows()>0) {
            return $query->result();
        }else{
            return false;
        }
    }
    /*
     * @method GetMessageById
     * @param $id
     * @return resultset Object row
     */
    public function GetMessageById($id){
         $query =$this->db->where('id',$id)->get('message');
        if ($query->num_rows()>0) {
            return $query->row();
        }else{
            return false;
        }
    }
    /*
     * @method AddMessage
     * @param prepend data
     * @return bool
     */
    public function AddMessage($data){
        return $this->db->insert('message',$data);
    }
    /*
     * @method AddToken
     * @param prepend array
     * @return bool
     */
    public function AddToken($data){
        return $this->db->insert('token',$data);
    }
    /*
     * @method AddSocialNetworkUser
     * @param prepend array
     * @return bool
     */
    public function AddSocialNetworkUser($data){
        $userdata=false;
        $chekcUser = $this->db
                            ->where('network = '.$data['network'].' OR email='.$data['email'])
                            ->where("type_id",3)
                            ->get('user');
        
         if ($chekcUser->num_rows() > 0){
                $this->db
                        ->where('network = '.$data['network'].' OR email='.$data['email'])
                        ->where("type_id",3)
                        ->update('user',$data);
                
                $userdata=$this->db->where('network',$data['network'])->get('user');
         }else{
                $this->db->insert('user',$data);
                $userdata=$this->db->where('id',$this->db->insert_id())->get('user');
         }
         if($userdata){
             return $userdata->row();
         }else{
             return false;
         }
    }
    /*
     * @method audit
     * @param $data
     * @return bool
     */
    public function audit($data){
        return $this->db->insert("audit",$data);
    }
    /*
     * @method UpdateMessage
     * @param id
     * @param prepend
     * @return bool
     */
    public function UpdateMessage($id,$data){
        return $this->db->where('id',$id)->update('message',$data);
    }
    
    /*
     * @method DeleteSingleRowById
     * @param table
     * @param id
     * @retrun bool
     */
    public function DeleteSingleRowById($table,$id){
        $imagesQuery;
        switch ($table){
            case "user":
                $imagesQuery=$this->db->select('image')->where('id',$id)->get($table);
                if ($imagesQuery->num_rows()) {
                    $imagesQuery =$imagesQuery->row();
                    if($imagesQuery->image!=null){
                        unlink("./assets/media/user/".$imagesQuery->image);
                        unlink("./assets/media/user/thumb/".$imagesQuery->image);
                    }
                  
               }
                break;
            case "campaign":
                 $imagesQuery=$this->db->select('image')->where('id',$id)->get($table);
                if ($imagesQuery->num_rows()) {
                     $imagesQuery =$imagesQuery->row();
                     if($imagesQuery->image!=null){
                        unlink("./assets/media/campaign/".$imagesQuery->image);
                        unlink("./assets/media/campaign/thumb/".$imagesQuery->image);
                     }
               }
                $this->db->where('campaign_id',$id)->delete('campaign_has_user');
                $this->db->where('campaign_id',$id)->delete('campaign_has_hotspot');
                $this->db->where('campaign_id',$id)->delete('answer_survey');
                break;
            case "gallery":
                $imagesQuery=$this->db->where("gallery_id",$id)->delete("gallery_items");
                if($imagesQuery){
                    delTreeFolder("./assets/media/gallery/".$id);
                }
               break;
        }
        return $this->db->where("id",$id)->delete($table);
    }
}