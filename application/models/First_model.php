<?php

class First_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    function get_rute($search=""){
//        $search = trim($search);
//        $where = "";
//        if($search){
//            $where = " AND bmr_city LIKE '%".$search."%'";
//        }
//        $query = "SELECT * FROM `bijbmobile_rute` WHERE 1".$where." ORDER BY bmr_group DESC, bmr_city ASC LIMIT 30";
//        $result = $this->db->query($query)->result_array();
        
        if($search){
            $this->db->like('bmr_city', trim($search));
        }
        $this->db->order_by('bmr_group', 'DESC');
        $this->db->order_by('bmr_city', 'ASC');
        $this->db->limit(30);
        $result = $this->db->get('bijbmobile_rute')->result_array();
        
        return $result;
    }
    
    function check_login($value) {
        $result = $this->db->get_where('bijbmobile_users', array('bmu_phone' => $value['bmu_phone'], 'bmu_password' => $value['bmu_password']))->result();
//        $result = $this->db->query("SELECT * FROM `bijbmobile_users` WHERE bmu_phone = '{$value['bmu_phone']}' AND bmu_password = '{$value['bmu_password']}'")->result();

        return $result;
    }

    function check_users($field, $value) {
        $this->db->select($field)->from('bijbmobile_users');
        $this->db->where($field, $value);
        $result = $this->db->get()->result();
//        $result = $this->db->query("SELECT {$field} FROM `bijbmobile_users` WHERE {$field} = '{$value}'")->result();

        return count($result);
    }
    
    function check_users2($field, $value) {
        $this->db->select($field)->from('bijbmobile_users');
        $this->db->where($field, $value);
        $result = $this->db->get()->result();
//        $result = $this->db->query("SELECT {$field} FROM `bijbmobile_users` WHERE {$field} = '{$value}'")->result();

        return $result;
    }

    function check_users_asktoyou($field, $value) {
        $this->db->select($field)->from('bijbmobile_asktoyou');
        $this->db->where($field, $value);
        $result = $this->db->get()->result();
//        $result = $this->db->query("SELECT {$field} FROM `bijbmobile_asktoyou` WHERE {$field} = '{$value}'")->result();

        return count($result);
    }
    
    function get_users($field, $value) {
        $this->db->select('*')->from('bijbmobile_users');
        $this->db->where($field, $value);
        $result = $this->db->get()->result();
//        $result = $this->db->query("SELECT * FROM `bijbmobile_users` WHERE {$field} = '{$value}'")->result();

        return $result;
    }

    function get_point($id) {
        $this->db->select('SUM(bmp_point) as points')->from('bijbmobile_point');
        $this->db->where('bmu_id', $id);
        $result = $this->db->get()->result();
//        $result = $this->db->query("SELECT SUM(bmp_point) as points FROM `bijbmobile_point` WHERE bmu_id = '{$id}'")->result();

        return number_format($result[0]->points, 1, ',', '');
    }

    function get_point_list($id) {
        $this->db->select('*')->from('bijbmobile_point');
        $this->db->where('bmu_id', $id);
        $result = $this->db->get()->result();
//        $result = $this->db->query("SELECT * FROM `bijbmobile_point` WHERE bmu_id = '{$id}'")->result();

        return $result;
    }

    function get_point_new($id) {
        $this->db->select('COUNT(bmu_id) as points')->from('bijbmobile_users');
        $this->db->where('bmu_sharefrom', $id);
        $this->db->where('(bmu_deviceid IS NOT NULL OR bmu_datecreate <= \'2018-05-20\')');
        $result = $this->db->get()->result();
//        $result = $this->db->query("SELECT COUNT(bmu_id) as points FROM `bijbmobile_users` WHERE bmu_sharefrom = '{$id}' AND (bmu_deviceid IS NOT NULL OR bmu_datecreate <= '2018-05-20')")->result();

        return number_format(($result[0]->points*10), 1, ',', '');
    }

    function get_point_list_new($id) {
        $this->db->select('*')->from('bijbmobile_users');
        $this->db->where('bmu_sharefrom', $id);
        $this->db->where('(bmu_deviceid IS NOT NULL OR bmu_datecreate <= \'2018-05-20\')');
        $result = $this->db->get()->result();
//        $result = $this->db->query("SELECT * FROM `bijbmobile_users` WHERE bmu_sharefrom = '{$id}' AND (bmu_deviceid IS NOT NULL OR bmu_datecreate <= '2018-05-20')")->result();

        return $result;
    }

    function get_point_list_pemenang(){
        $query = 'SELECT a.*,(SELECT COUNT(bmu_sharefrom) as jml FROM `bijbmobile_users` WHERE bmu_sharefrom = a.bmu_shareid) as jml2, (SELECT bmu_datecreate FROM `bijbmobile_users` WHERE bmu_sharefrom = a.bmu_shareid ORDER BY bmu_datecreate DESC LIMIT 1) as orderpoint  FROM `bijbmobile_users` a WHERE `bmu_shareid` IN(SELECT bmu_sharefrom  FROM `bijbmobile_users` WHERE `bmu_sharefrom` IS NOT NULL AND `bmu_sharefrom` != "" AND (bmu_deviceid IS NOT NULL OR bmu_datecreate <= "2018-05-20") AND bmu_datecreate <= "2018-05-23 23:59:59") ORDER BY jml2 DESC, orderpoint ASC';
        $result = $this->db->query($query)->result();

        return $result;
    }

    function check_users_device($field, $value) {
        $this->db->select($field)->from('bijbmobile_users_device');
        $this->db->where($field, $value);
        $result = $this->db->get()->result();
//        $result = $this->db->query("SELECT {$field} FROM `bijbmobile_users_device` WHERE {$field} = '{$value}'")->result();

        return count($result);
    }

    function get_alert_flight_list($id, $fno="", $fdate="", $limit="10") {
        $this->db->select('*')->from('bijbmobile_alert_flight');
        $this->db->where('bmu_id', $id);
        if($fno){
            $this->db->where('bmaf_flight_number', $fno);
        }
        if($fdate){
            $this->db->where('bmaf_flight_date', $fdate);
        }
        $this->db->where('bmaf_status', '1');
        $this->db->order_by('bmaf_id', 'DESC');
        $this->db->limit($limit);
        $result = $this->db->get()->result();
        
//        $query = "";
//        if($fno){
//            $query .= " AND bmaf_flight_number = '{$fno}'";
//        }
//        if($fdate){
//            $query .= " AND bmaf_flight_date = '{$fdate}'";
//        }
//        $result = $this->db->query("SELECT * FROM `bijbmobile_alert_flight` WHERE bmu_id = '{$id}' AND bmaf_status = '1' ".$query." ORDER BY bmaf_id DESC LIMIT ".$limit)->result();

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
