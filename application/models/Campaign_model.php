<?php
class Campaign_model extends CI_Model{
    
    public function __construct() {
        parent::__construct();
    }
    /*
     * @method GetCampaigns
     * @return resultset Object result
     */
    public function GetCampaigns(){
        $query =$this->db->get('campaign');
        if ($query->num_rows()>0) {
            return $query->result();
        }else{
            return false;
        }
    }
    /*
     * @method GetCampaignById
     * @param $id
     * @return resultset Object row
     */
    public function GetCampaignById($id){
        $query =$this->db->where('id',$id)->get('campaign');
        if ($query->num_rows()>0) {
            return $query->row();
        }else{
            return false;
        }
    }
    /*
     * @method GetAnswersSurvey
     * @param campaign_id
     * @return resultset answers data
     */
    public function GetAnswersSurvey($campaign_id){
        $query =$this->db->where('campaign_id',$campaign_id)->get('answer_survey');
        if ($query->num_rows()>0) {
            return $query->result();
        }else{
            return false;
        }
    }
    /*
     * @method GetHostSpotsOnCampaign
     * @param campaign_id
     * @return resultset hotspot data
     */
    public function GetHostSpotsOnCampaign($campaign_id){
        $query =$this->db->where('campaign_id',$campaign_id)->get('campaign_has_hotspot');
        if ($query->num_rows()>0) {
            return $query->result();
        }else{
            return false;
        }
    }
    /*
     * @method GetFiltersOnCampaign
     * @param campaign_id
     * @return resultset hotspot data
     */
    public function GetFiltersOnCampaign($campaign_id){
        $query =$this->db->where('campaign_id',$campaign_id)->get('campaign_filters');
        if ($query->num_rows()>0) {
            return $query->result();
        }else{
            return false;
        }
    }
    /*
     * @method AddCampaign
     * @param prepend array
     * @return bool
     */
    public function AddCampaign($data){
        return $this->db->insert('campaign',$data);
    }
     /*
     * @method AddCampaignUser
     * @param prepend
     * @return bool
     */
    public function AddCampaignUser($data){
        return $this->db->insert('campaign_has_user',$data);
    }
    /*
     * @method AddCampaignHotspot
     * @param prepend
     * @return bool
     */
    public function AddCampaignHotspot($data){
        return $this->db->insert('campaign_has_hotspot',$data);
    }
    /*
     * @method AddAnswerToSurvey
     * @param prepend
     * @return bool
     */
    public function AddAnswerToSurvey($data){
        return $this->db->insert('answer_survey',$data);
    }
    /*
     * @method AddCampaignFilters
     * @param prepend array
     * @return bool
     */
    public function AddCampaignFilters($data){
        return $this->db->insert('campaign_filters',$data);
    }
    /*
     * @method UpdateCampaign
     * @param id_hotspot
     * @param data
     * @return bool
     */
    public function UpdateCampaign($id_hotspot,$data){
        return $this->db->where('id',$id_hotspot)->update('campaign',$data);
    }
   /*
    * @method DeleteRelations
    * @param table
    * @param campaignId
    * return id
    */
    public function DeleteRelations($campaignId){
        $query=false;
        $query=$this->db->where('campaign_id',$campaignId)->delete('campaign_filters');
        $query=$this->db->where('campaign_id',$campaignId)->delete('campaign_has_hotspot');
        $query=$this->db->where('campaign_id',$campaignId)->delete('answer_survey');
        return $query;
    }
}

