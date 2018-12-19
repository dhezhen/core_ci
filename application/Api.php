<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends MY_Controller {

	public function __construct() {
		parent::__construct();

		// model loader
		$this->load->model("Presence_model");
		// library loader
	}

	public function addDuty() {
		// $data = $this->input->post("duty");

		// echo json_decode($this->input->post());

		$data = json_decode(file_get_contents('php://input'),true);
		$perdin = $data["perdin"];
		$peserta = $data["peserta"];

		foreach($peserta as $value) {
			$data = array(
				"date_insert"			=> date("Y-m-d h:i:s"),
				"id_employee"			=> $value["nik"],
				"id_employee_parent"	=> 0,
				"date_start"			=> $perdin["tgl_berangkat"],
				"date_end"				=> $perdin["tgl_kembali"],
				"between_time"			=> 0,
				"start"					=> "",
				"finish"				=> "",
				"id_state"				=> 5, // id of duty
				"id_office"				=> 1, // id BIJB PUSAT
				"id_leave_balance"		=> 0,
				"description"			=> $perdin["keperluan"],
				"approve"				=> "y",
				"datetime_update"		=> date("Y-m-d h:i:s"),
				"temp_oid"				=> "",
				"temp_hari"				=> "",
				"temp_prescid"			=> ""
				);

			$this->Presence_model->insert($data);
		}


		// $this->Presence_model->insert_batch($data);
	}
}
?>