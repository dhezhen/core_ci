<?php

class Fourth_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }
    
    function get_all_paketdt() {
        $this->db->where("bmd_aktif",1);
        $this->db->where("bmd_nama_vendor","Dream Tour");
        $result = $this->db->get('bijbmobile_hajidanumroh')->result();
        // $result = $this->db->query("SELECT * FROM `bijbmobile_dreamtour` WHERE bmd_aktif = 1")->result();
 
        return $result;
    }  
	
  function get_all_vendor() {
        $this->db->where("bmd_vendor_aktif",1);
        $this->db->where("bmd_kode_vendor","HU01");
               $result = $this->db->get('vendor')->result();
        // $result = $this->db->query("SELECT * FROM `bijbmobile_dreamtour` WHERE bmd_aktif = 1")->result();
 
        return $result;
    }  

	/* vendor hotel */
 function get_all_vendorhotel() {
        $this->db->where("bmd_vendor_aktif",1);
        $this->db->where("bmd_kode_vendor","HR02");
               $result = $this->db->get('vendor')->result();
        // $result = $this->db->query("SELECT * FROM `bijbmobile_dreamtour` WHERE bmd_aktif = 1")->result();
 
        return $result;
    }  

	function get_all_vendortour() {
        $this->db->where("bmd_vendor_aktif",1);
        $this->db->where("bmd_kode_vendor","TT03");
               $result = $this->db->get('vendor')->result();
        // $result = $this->db->query("SELECT * FROM `bijbmobile_dreamtour` WHERE bmd_aktif = 1")->result();
 
        return $result;
    }  

		
	
	function get_all_promo() {
	$this->db->where_in("bmd_nama_vendor","Dream Tour");
        $this->db->where("bmd_aktif", 1);
        $result = $this->db->get('bijbmobile_hajidanumroh')->result();
        // $result = $this->db->query("SELECT * FROM `bijbmobile_dreamtour` WHERE bmd_aktif = 1")->result();
 
        return $result;
    }
	
	function get_all_promo1() {
	$this->db->where_in("bmd_nama_vendor","Dini Group Indonesia");
        $this->db->where("bmd_aktif", 1);
        $result = $this->db->get('bijbmobile_hajidanumroh')->result();
        // $result = $this->db->query("SELECT * FROM `bijbmobile_dreamtour` WHERE bmd_aktif = 1")->result();
 
        return $result;
    }

	
	
	
	
    function get_all_hajidanumroh_by_pesanan($id=""){
        $result = array();
        if($id){
            $this->db->where_in("bmd_id", $id);
            $this->db->where("bmd_aktif", 1);
            $result = $this->db->get('bijbmobile_hajidanumroh')->result();
            // $result = $this->db->query("SELECT * FROM `bijbmobile_dreamtour` WHERE bmd_id IN(".$id.") AND bmd_aktif = 1")->result();
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
