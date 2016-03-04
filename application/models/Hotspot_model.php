<?php
class Hotspot_model extends CI_Model{
    
    public function __construct() {
        parent::__construct();
    }

    /*
     * @method GetHotSpots
     * @return resultset Object result
     */
    public function GetHotSpots(){
        $query =$this->db->get('hotspot');
        if ($query->num_rows()>0) {
            return $query->result();
        }else{
            return false;
        }
    }
    /*
     * @method GetHotSpotById
     * @param $id
     * @return resultset Object row
     */
    public function GetHotSpotById($id){
        $query =$this->db->where('id',$id)->get('hotspot');
        if ($query->num_rows()>0) {
            return $query->row();
        }else{
            return false;
        }
    }
    /*
     * @method AddHotSpot
     * @param prepend array
     * @return bool
     */
    public function AddHotSpot($data){
        return $this->db->insert('hotspot',$data);
    }
    /*
     * @method UpdateHotSpot
     * @param id_hotspot
     * @param data
     * @return bool
     */
    public function UpdateHotSpot($id_hotspot,$data){
        return $this->db->where('id',$id_hotspot)->update('hotspot',$data);
    }
}

