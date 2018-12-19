<?php

class Third_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }
    
    function get_all_koperasi() {
        $this->db->where("bmk_aktif", 1);
        $result = $this->db->get('bijbmobile_koperasi')->result();
        // $result = $this->db->query("SELECT * FROM `bijbmobile_koperasi` WHERE bmk_aktif = 1")->result();

        return $result;
    }

    function get_all_koperasi_by_pesanan($id=""){
        $result = array();
        if($id){
            $this->db->where_in("bmk_id", $id);
            $this->db->where("bmk_aktif", 1);
            $result = $this->db->get('bijbmobile_koperasi')->result();
            // $result = $this->db->query("SELECT * FROM `bijbmobile_koperasi` WHERE bmk_id IN(".$id.") AND bmk_aktif = 1")->result();
        }

        return $result;
    }
    
    function insertdata($table, $data) {
        if ($this->db->insert($table, $data)) {
            return "sukses";
        } else {
            return "gagal";
        }
    }

    function insertdatareturnid($table, $data) {
        if ($this->db->insert($table, $data)) {
            return $this->db->insert_id();
        } else {
            return "gagal";
        }
    }

    function updatedata($table, $data, $id) {
        if ($this->db->update($table, $data, $id)) {
            return "sukses";
        } else {
            return "gagal";
        }
    }

    function deletedata($table, $field, $id) {
        if ($this->db->delete($table, array($field => $id))) {
            return "sukses";
        } else {
            return "gagal";
        }
    }

}
