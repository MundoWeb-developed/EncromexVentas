<?php
defined('BASEPATH') OR exit('No direct script access allowed'); 

class Subzone_model extends CI_Model {


    public function all(){
        $this->db->select('subzonas.*, zonas.zona');
        $this->db->from('subzonas');
        $this->db->join('zonas', 'zonas.id = subzonas.zone_id','left');
        $this->db->where('subzonas.status', 1);
        $this->db->order_by('subzonas.id', 'DESC');
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return false;
   }

    public function store($data = []){
        $this->db->insert('subzonas', $data);         
        return true;
    }

    public function getById($id){
        return $this->db->select('*')->from('subzonas')->where('id', $id)->get()->row_array();
    }

    public function update($data = [], $id){
        $this->db->where('id', $id);
        $this->db->update('subzonas', $data);
        return true;
    }

    public function getByZone($id){
        return $this->db->select('*')->from('subzonas')->where('zone_id', $id)->get()->result_array();
    }
}