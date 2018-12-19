<?php

class Second_model extends CI_Model {

    public function __construct() {
        parent::__construct();
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
