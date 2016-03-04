<?php
class User_model extends CI_Model{
    
    
    public function __construct() {
        parent::__construct();
    }
    /*
     * @method checkUserEmail
     * @param email
     * @param type
     * return bool
     */
    public function checkUserEmail($email,$type){
        $query =$this->db->where('email',$email)->where('type_id',$type)->get('user');
        if ($query->num_rows()>0) {
            return true;
        }else{
            return false;
        }
    }
    
    
    /*
     * @method GetUsers
     * @return resultset Object row
     */
    public function GetUsers(){
        $query =$this->db->get('user');
        if ($query->num_rows()>0) {
            return $query->result();
        }else{
            return false;
        }
    }
    /*
     * @method GetUserById
     * @param $id
     * @return resultset Object row
     */
    public function GetUserById($id){
        $query =$this->db->where('id',$id)->get('user');
        if ($query->num_rows()>0) {
            return $query->row();
        }else{
            return false;
        }
    }
    /*
     * @method AddUser
     * @param prepend array
     * @return bool
     */
    public function AddUser($data){
        return $this->db->insert('user',$data);
    }
    /*
     * @method UpdateUser
     * @param id_user
     * @param data
     * @return bool
     */
    public function UpdateUser($id_user,$data){
        return $this->db->where('id',$id_user)->update('user',$data);
    }
}

