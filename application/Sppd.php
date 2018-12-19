<?php
class Sppd extends CI_Controller {
	public function __construct() {
		parent::__construct();  
		date_default_timezone_set('Asia/Jakarta');
		
		$this->load->model(array('Sppd_model','Model_user','Model_surat','Model_keuangan'));
		$this->load->model('Budgets_model');
		$this->load->model('Ass_mobil_model');
		$this->load->model('Fin_coa_model');
		$this->load->model('Fin_anggaran_model');
		$this->load->model("Fin_pengajuan_dana_model");
		$this->load->model("Fin_detail_pengajuan_dana_model");
		$this->load->model("Fin_ca_model");
		$this->load->model("Fin_transaksi_model");
		$this->load->model('Perdin_model');
		$this->load->model('Perdin_peserta_model');
		$this->load->model('Perdin_rincian_model');
		$this->load->model('Pkp_model');
		$this->load->model('Pajak_model');
		$this->load->model("Users_model");
		$this->load->model("User_menu_model");
		$this->load->model("Surat_model");
		$this->load->library('pdf');
		$this->load->library("gcm");
		header("Access-Control-Allow-Origin: *");
		
	}

	private function sendNotif($message, $to) {
		$this->gcm->setData($message, $to, "BIJB Apps", "SPPD");
		$this->gcm->sendNotif();
	}

	private function getUserIdByName($name) {
		$user = $this->Users_model->select("id")
									->find_by("user_name", $name);

		return ($user) ? $user->id : 0;
	}

	private function getPicId($pic) {
		$list = array(
			"tester"		=> 76,
			"personalia"	=> 33,
			"hcm"			=> 32,
			"bc"			=> 109,
			"pajak"			=> 29,
			"keuangan"		=> 28,
			"dirkeu"		=> 79,
			"dirut"			=> 63,
			"pencairan"		=> 30
			);

		return $list[$pic];
	}
	
	public function index()
	{ 
		is_logged();
		$data['data']['dataPeserta'] = $this->Sppd_model->get_peserta();
		$data['data']['activeMenu']	= "sppd-pengajuansppd";
		$data['title']			=	'Pengajuan SPPD';
		$this->load->view('header');
		$this->load->view('menu',$data);
		$this->load->view('page/sppd_index');
		$this->load->view('footer');
		// $this->load->view('templates/template_wizard',$data);  
		// $this->load->view('templates/template_wizard');  
	}
	
	public function data_sppd()
	{
		is_logged();
		$data['data']['dataSppd'] = $this->Sppd_model->get_pengajuan_sppd();
		$data['data']['activeMenu']	= "sppd-datasppd";
		$data['title']			= 'Data Pengajuan SPPD';
		
		$this->load->view('header');
		$this->load->view('menu',$data);
		$this->load->view('page/sppd_data');
		$this->load->view('footer');  
	}

	public function modal_data_sppd_sarana() {
		$idPerdin = $this->input->post("idPerdin");

		$data["sarana"] = $this->Perdin_model->select("users.user_name, ass_mobil.nama, ass_mobil.nomor")
												->join("ass_mobil", "ass_mobil.id = perdin.mobil_id", "left")
												->join("users", "users.user_id = perdin.driver_id", "left")
												->find($idPerdin);

		$this->load->view("page/sppd/modal_data_sppd_sarana", $data);
	}
	
	public function approve_atasan()
	{ 
		is_logged();
		$data['data']['dataSppd'] = $this->Sppd_model->get_pengajuan_sppd_bawahan();
		$data['data']['activeMenu']	= "sppd-approveatasan";
		$data['data']['view'] = "sppd/approve_atasan";
		$data['title']			=	'Persetujuan Atasan';

		$this->load->view('header');
		$this->load->view('menu',$data);
		$this->load->view('page/sppd_approve_atasan');
		$this->load->view('footer'); 
	} 

	public function popup_atasan() {
		//is_logged();
		// for approval hcm
		if($this->input->post("approve")) {
			// if approve
			$data = array(
				"status"				=> "Approval Atasan",
				"time_approval_atasan"	=> date("Y-m-d H:i:s"),
				"approval_atasan"		=> $this->session->userdata("user_login")
				);

			if($this->Perdin_model->update($this->input->post("idPerdin"), $data)) {
				// set notification to creator

				// set notification to tester
				$this->sendNotif("Terdapat pengajuan yang harus diapprove oleh personalia", $this->getPicId("tester"));
				// set notification to personalia
				$this->sendNotif("Terdapat pengajuan yang harus diapprove oleh personalia", $this->getPicId("personalia"));
				// set success message
				redirect(base_url()."sppd/approve_atasan");
			}
		}
		if($this->input->post("reject")) {
			$data = array(
				"status"				=> "Not Approve Atasan",
				"approval_atasan"		=> $this->session->userdata("user_login")
				);

			if($this->Perdin_model->update($this->input->post("idPerdin"), $data)) {
				redirect(base_url()."sppd/approve_atasan");
			}
		}

		$data["idPerdin"] = $this->input->post("idPerdin");
		$data["type"] = $this->input->post("type");

		$this->load->view("page/sppd/modal_confirmation", $data);

		// echo json_encode($data["anggaran"]);
	}

	private function getSppdAtasanById($id) {
		$atasan = $this->Users_model->select("a.id, a.user_name, a.posisi")
											->join("users a", "a.id = users.sppd_atasan")
											->find($id);

		return $atasan;
	}

	public function testNotif() {
		$receiver = "76,66,112";
		$message = "Test";

		$this->sendNotification($receiver, $message);
	}

	private function sendNotification($receiver, $message) {
		/*
		switch($type) {
			case("pengajuan") :
				// notif ke atasan
				// cari id atasan
				$data = $this->getSppdAtasanById($id);
				// send notif ke atasan
				$message1 = "Ada pengajuan SPPD yang harus mendapatkan persetujuan atasan";
				$message2 = "Pengajuan SPPD telah dibuat";

				$this->sendNotification($atasan->id, $message1);
				$this->sendNotification($id, $message2);
			break;
			case("approve_atasan") :
				$message1 = "Ada pengajuan SPPD yang harus mendapatkan persetujuan personalia";
				$message2 = "Pengajuan SPPD telah mendapatkan persetujuan atasan";
				// notif ke personalia
				$this->sendNotification($idPersonalia, $message1);
				// notif ke pengaju
				$this->sendNotification($id, $message2);
			break;
			case("approve_personalia") :
				// notif ke hcm
				// notif ke pengaju
			break;
			case("approve_hcm") :
				// notif ke budget_control
				// notif ke pengaju
			break;
			case("approve_budget_control") :
				// notif ke akunting
				// notif ke pengaju
			break;
			case("approve_akunting") :
				// notif ke keuangan
				// notif ke pengaju
			break;
			case("approve_keuangan") :
				// (jika < 10jt)
					// notif ke pengaju
					// notif ke pencairan (opsional)
				// (jika < 250jt)
					// notif ke pengaju
					// notif ke dir_keuangan
					// notif ke pencairan (opsional)
			break;
			case("approve_dir_keuangan") :
				// (jika < 250jt)
					// notif ke pengaju
					// notif ke pencairan (opsional)
				// (else)
					// notif ke pengaju
					// notif ke dir_utama
					// notif ke pencairan (opsional)
			break;
			case("approve_dir_utama") :
				// notif ke pengaju
				// notif ke pencairan (opsional)
			break;
			case("approve_pencairan") :
				// notif ke pengaju
			break;
			case("realisasi") :
				// notif ke personalia
			break;
			case("approve_realisasi") :
				// notif ke pengaju
				// notif ke ca
			break;
			case("approve_ca") :
				// notif ke pengaju
			break;
		}
		*/

		$headers = null;

		$msg = array(
			"message"	=> $message,
			"list_id"	=> $receiver,
			"title"		=> "BIJB Apps",
			"open_page"	=> "sppd"
			);

		$fields = array(
			"data"		=> $msg
			);

		$ch = curl_init();

	    curl_setopt($ch, CURLOPT_URL, 'http://localhost/proker/home_api/send_notification');
	    curl_setopt($ch, CURLOPT_POST, true);
	    // curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($msg));

	    $result = curl_exec($ch);
	    curl_close($ch);
	}
	
	public function approve_sdm()
	{ 
		is_logged();
		$data['data']['dataSppd'] = $this->Sppd_model->get_sppd_personalia();
		$data['title']			=	'Persetujuan Personalia';

		$this->load->view('header');
		$this->load->view('menu',$data);
		$this->load->view('page/sppd_approve_adminsdm');
		$this->load->view('footer');   
	}

	public function popup_sdm() {
		// for approval sdm
		if($this->input->post("submit")) {
			// do update here
			$id = $this->input->post("idPerdin");

			$data = array(
				"status"					=> "Approval Admin Personalia",
				"time_approval_adminsdm"	=> date("Y-m-d H:i:s"),
				"approval_adminsdm"			=> $this->session->userdata("user_login")
				);

			if($this->Perdin_model->update($id, $data)) {
				// set notification to creator

				// set notification to HCM
				$this->sendNotif("Terdapat pengajuan yang harus diapprove oleh HCM", $this->getPicId("hcm"));
				// set success message
				// redirect to sdm page
				redirect(base_url()."sppd/approve_sdm");
			}
		}

		$month = date("m");
		$year = date("Y");

		$data["idPerdin"] = $this->input->post("idPerdin");

		if($this->is_director($this->input->post("idPerdin"))) {
			$data["anggaran"] = $this->Fin_anggaran_model->find_by(array("nama" => "Perjalanan Dinas Direksi", "MONTH (month) = " => $month, "YEAR (month) = " => $year));
			// uangmakan_saku":"275000","uangtransportasi":"600000","uangpenginapan":"750000","uangdalamkota":"400000
			// $data["pengajuan"]	= $this->Perdin_model->select("*")
		}
		else {
			$data["anggaran"] = $this->Fin_anggaran_model->find_by(array("nama" => "Perjalanan Dinas Staf", "MONTH (month) = " => $month, "YEAR (month) = " => $year));
			// uangmakan_saku":"275000","uangtransportasi":"600000","uangpenginapan":"750000","uangdalamkota":"400000
			// $data["pengajuan"]	= $this->Perdin_model->select("*")
		}
		
		$pengajuan	= $this->Perdin_model->select("(SUM(uangmakan_saku)*jml_hari) AS ums, SUM(uangtransportasi) AS ut, (SUM(uangpenginapan)*menginap) AS up, SUM(uangdalamkota) AS udk")
													->join("perdin_peserta", "perdin_peserta.no_sppd = perdin.id")
													->group_by("perdin_peserta.no_sppd")
													->find($this->input->post("idPerdin"));

		$data["pengajuan"]	= $pengajuan->ums + $pengajuan->ut + $pengajuan->up + $pengajuan->udk;

		$this->load->view("page/sppd/modal_approve_sdm", $data);
	}
	
	public function approve_hcm()
	{ 
		is_logged();
		$data['data']['dataSppd'] = $this->Sppd_model->get_sppd_hcm();
		$data['data']['activeMenu']	= "sppd-approvehcm";
		$data['data']['view'] = "sppd/approve_hcm";
		$data['title']			=	'Persetujuan HCM';
		$this->load->view('header');
		$this->load->view('menu',$data);
		$this->load->view('page/sppd_approve_hcm');
		$this->load->view('footer');
	}

	public function popup_hcm() {
		is_logged();
		// for approval hcm
		if($this->input->post("reject")) {
			$id = $this->input->post("idPerdin");

			$data = array(
				"status"			=> "Not Approve HCM",
				"time_approval_sdm"	=> date("Y-m-d H:i:s"),
				"approval_sdm"		=> $this->session->userdata("user_login")
			);

			if($this->Perdin_model->update($id, $data)) {
				redirect(base_url()."sppd/approve_hcm?status=success");
			}
			else {
				redirect(base_url()."sppd/approve_hcm?status=failed");
			}
		}
		if($this->input->post("submit")) {
			// do update here
			$id = $this->input->post("idPerdin");

			$data = array(
				"status"			=> "Approval HCM",
				"time_approval_sdm"	=> date("Y-m-d H:i:s"),
				"approval_sdm"		=> $this->session->userdata("user_login")
				);

			if($this->Perdin_model->update($id, $data)) {
				// insert ke fin_pengajuan_dana

				// insert ke fin_detail_pengajuan_dana

		
				// get data perdin
				$dataPerdin = $this->Perdin_model->select("perdin.tgl_insert, perdin.user_insert, perdin.jml_hari, keperluan, menginap, SUM(uangmakan_saku) AS ums, SUM(uangtransportasi) AS ut, SUM(uangpenginapan) AS up, SUM(uangdalamkota) AS udk")
													->join("perdin_peserta", "perdin_peserta.no_sppd = perdin.id")
													->find($this->input->post("idPerdin"));

				// echo json_encode($dataPerdin);

				// remove if id perdin exist inside table fin_pengajuan_dana and fin_detail_pengajuan_dana
				if($this->Fin_pengajuan_dana_model->find_by("perdin_id", $this->input->post("idPerdin"))) {
					$this->Fin_pengajuan_dana_model->delete_where(array("fin_pengajuan_dana.perdin_id" => $this->input->post("idPerdin")));
				}

				// insert to pengajuan
				$data = array(
					"inserted_by"		=> $this->session->userdata("user_login"),
					"tgl_pengajuan"		=> $dataPerdin->tgl_insert,
					"tgl_penggunaan"	=> date("Y-m-d"),
					"nama_pengaju"		=> $dataPerdin->user_insert,
					"nama_pengajuan"	=> $dataPerdin->keperluan,
					"perdin_id"			=> $this->input->post("idPerdin")
					);

				$pengajuan = $this->Fin_pengajuan_dana_model->insert($data); //

				// get anggaran id by name pengajuan dana and current month
				$month 				= date("m"); // get month for month filtering
				// $anggaran			= $this->Fin_anggaran_model->select("*")
																// ->find_by(array("nama" => "Perjalanan Dinas", "MONTH(month)" => $month));

				if($this->is_director($this->input->post("idPerdin"))) {
					// echo "anggaran direksi, perbaiki fitur search by nama anggaran";
					$anggaran = $this->Fin_anggaran_model->find_by(array("nama" => "Perjalanan Dinas Direksi", "MONTH (month) = " => $month, "YEAR (month) = " => $year));
					// uangmakan_saku":"275000","uangtransportasi":"600000","uangpenginapan":"750000","uangdalamkota":"400000
					// $data["pengajuan"]	= $this->Perdin_model->select("*")
				}
				else {
					// echo "anggaran staff, perbaiki fitur search by nama anggaran";
					$anggaran = $this->Fin_anggaran_model->find_by(array("nama" => "Perjalanan Dinas Staf", "MONTH (month) = " => $month, "YEAR (month) = " => $year));
					// uangmakan_saku":"275000","uangtransportasi":"600000","uangpenginapan":"750000","uangdalamkota":"400000
					// $data["pengajuan"]	= $this->Perdin_model->select("*")
				}

				$anggaran_id		= ($anggaran != false) ? $anggaran->id : "0";

				$harga_satuan = (($dataPerdin->ums * $dataPerdin->jml_hari) + $dataPerdin->ut + ($dataPerdin->up * $dataPerdin->menginap) + $dataPerdin->udk); //static for a while, when i think about total

				// gather perdin_peserta data added at 31032017
				$data2 = array(
					array( // Uang Makan Saku
						"pengajuan_id"		=> $pengajuan,
						"inserted_by"		=> $this->session->userdata("user_login"),
						"nama_perkiraan"	=> "Uang Makan Saku",
						"harga_satuan"		=> $dataPerdin->ums,
						"anggaran_id"		=> $anggaran_id,
						"jumlah"			=> 1,
						"total"				=> ($dataPerdin->ums*$dataPerdin->jml_hari),
						"realisasi"			=> ($dataPerdin->ums*$dataPerdin->jml_hari)
						),
					array( // Uang Transportasi
						"pengajuan_id"		=> $pengajuan,
						"inserted_by"		=> $this->session->userdata("user_login"),
						"nama_perkiraan"	=> "Uang Transportasi",
						"harga_satuan"		=> $dataPerdin->ut,
						"anggaran_id"		=> $anggaran_id,
						"jumlah"			=> 1,
						"total"				=> $dataPerdin->ut*1,
						"realisasi"			=> $dataPerdin->ut*1
						),
					array( // Uang Penginapan
						"pengajuan_id"		=> $pengajuan,
						"inserted_by"		=> $this->session->userdata("user_login"),
						"nama_perkiraan"	=> "Uang Penginapan",
						"harga_satuan"		=> ($dataPerdin->up * $dataPerdin->menginap),
						"anggaran_id"		=> $anggaran_id,
						"jumlah"			=> 1,
						"total"				=> ($dataPerdin->up * $dataPerdin->menginap)*1,
						"realisasi"			=> ($dataPerdin->up * $dataPerdin->menginap)*1
						),
					array( // Uang Dalam Kota
						"pengajuan_id"		=> $pengajuan,
						"inserted_by"		=> $this->session->userdata("user_login"),
						"nama_perkiraan"	=> "Uang Dalam Kota",
						"harga_satuan"		=> $dataPerdin->udk,
						"anggaran_id"		=> $anggaran_id,
						"jumlah"			=> 1,
						"total"				=> $dataPerdin->udk*1,
						"realisasi"			=> $dataPerdin->udk*1
						)
					);

				// $this->db->insert_batch("fin_detail_pengajuan_dana", $data2);
				$this->Fin_detail_pengajuan_dana_model->insert_batch($data2);
				$this->insertSurat($id);

				// set notification to creator

				// set notification to budget control
				$this->sendNotif("Terdapat pengajuan yang harus diapprove oleh Budget Control", $this->getPicId("bc"));
				// send data to sdm apps
				// enable until agreement of driver account
				$data["perdin"] = $this->dataPerdin($this->input->post("idPerdin"));
				$data["peserta"] = $this->dataPeserta($this->input->post("idPerdin"));

				$this->postCURL("http://localhost/sdm_ci/api/addDuty", json_encode($data));
				// set success message
				// redirect to hcm page
				redirect(base_url()."sppd/approve_hcm");
			}
		}
		$year = date("Y");
		$month = date("m");

		// echo $year."-".$month;
		// get anggaran_perdin
		$data["idPerdin"] = $this->input->post("idPerdin");

		if($this->is_director($this->input->post("idPerdin"))) {
			echo "anggaran direksi, perbaiki fitur search by nama anggaran";
			$data["anggaran"] = $this->Fin_anggaran_model->find_by(array("nama" => "Perjalanan Dinas", "MONTH (month) = " => $month, "YEAR (month) = " => $year));
			// uangmakan_saku":"275000","uangtransportasi":"600000","uangpenginapan":"750000","uangdalamkota":"400000
			// $data["pengajuan"]	= $this->Perdin_model->select("*")
		}
		else {
			echo "anggaran staff, perbaiki fitur search by nama anggaran";
			$data["anggaran"] = $this->Fin_anggaran_model->find_by(array("nama" => "Perjalanan Dinas", "MONTH (month) = " => $month, "YEAR (month) = " => $year));
			// uangmakan_saku":"275000","uangtransportasi":"600000","uangpenginapan":"750000","uangdalamkota":"400000
			// $data["pengajuan"]	= $this->Perdin_model->select("*")
		}

		$pengajuan	= $this->Perdin_model->select("(SUM(uangmakan_saku)*jml_hari) AS ums, SUM(uangtransportasi) AS ut, (SUM(uangpenginapan)*menginap) AS up, SUM(uangdalamkota) AS udk")
													->join("perdin_peserta", "perdin_peserta.no_sppd = perdin.id")
													->group_by("perdin_peserta.no_sppd")
													->find($this->input->post("idPerdin"));

		$data["pengajuan"]	= $pengajuan->ums + $pengajuan->ut + $pengajuan->up + $pengajuan->udk;

		$type = $this->input->post("type");

		if($type == "reject") {
			$data["type"] = $this->input->post("type");
			$this->load->view("page/sppd/modal_confirmation", $data);
		}
		else {
			$this->load->view("page/sppd/modal_approve_hcm", $data);
		}

		// echo json_encode($data["anggaran"]);
	}

	// Approve GA Area

	public function approve_ga() {
		is_logged();

		// $data['data']['dataSppd'] = $this->Sppd_model->get_pengajuan_sppd();
		$data['data']['dataSppd'] = $this->Sppd_model->get_sppd_ga();
		$data["title"]	= "Persetujuan GA";

		$this->load->view('header');
		$this->load->view('menu', $data);
		$this->load->view('page/sppd_approve_ga');
		$this->load->view('footer');
	}

	public function modal_ga_sarana() {
		if($this->input->post("submit")) {
			// this condition to update database mobil dan driver
			$idDriver = $this->Perdin_peserta_model->find_by(array("no_sppd" => $this->input->post("idPerdin"), "nama" => "Driver"));

			$data = array(
				"mobil_id"	=> $this->input->post("mobil")
				);

			if($this->Perdin_model->update($this->input->post("idPerdin"), $data)) {
				$data = array(
					"nama"	=> $this->input->post("driver")
					);

				if($this->Perdin_peserta_model->update($idDriver->id, $data)) {
					redirect(base_url()."sppd/approve_ga");
				}
			}
		}

		$test = $this->Perdin_peserta_model->where("no_sppd", $this->input->post("idPerdin"))
											->find_all();

		$data["isDriver"]	= $this->Perdin_peserta_model->find_by(array("no_sppd" => $this->input->post("idPerdin"), "nama" => "Driver"));

		$data["idPerdin"]	= $this->input->post("idPerdin");

		$data["driver"]		= $this->Users_model->where("posisi", "Driver")
												->find_all();
		
		$data["mobil"]		= $this->Ass_mobil_model->find_all();

		$data["dataPerdin"]	= $this->Perdin_model->find($data["idPerdin"]);

		$this->load->view("page/sppd/modal_ga_sarana", $data);
	}

	// Approve Accounting Area

	public function approve_akunting() {
		is_logged();

		$data['data']['dataSppd']	= $this->Sppd_model->get_sppd_accounting();
		$data['data']['activeMenu']	= "sppd-approve_akunting";
		//$data['data']['view'] 		= "sppd/approve_accounting";
		$data['title'] 				= "Persetujuan Akunting";

		$this->load->view('header');
		$this->load->view('menu', $data);
		$this->load->view('page/sppd_approve_accounting');
		$this->load->view('footer');
	}

	public function edit_sppd_akunting() {
		is_logged();

		// cek variable global set or not?
		// if set

		//id perdin dari global

		// fungsi query baru view perdin w/ pkp

		// jika tidak
		$idPerdin = $this->input->post('idPerdin');

		$dataPerdin =  $this->Sppd_model->view_perdin_modal();
		// endif;

		$data['formData']	= $this->Sppd_model->view_perdin_fpd();
		$formData 			= $data['formData'];
		$data['dataEdit'] 	= $dataPerdin;
		$data["idPerdin"] 	= $idPerdin;

		// get finance for budget_control with Perjalanan Dinas name
		$month 				= date("m"); // get month for month filtering
		if($this->is_director($idPerdin)) {	
			$anggaran			= $this->Fin_anggaran_model->select("*")
															->find_by(array("nama" => "Perjalanan Dinas Direksi", "MONTH(month)" => $month));
		}
		else {
			$anggaran			= $this->Fin_anggaran_model->select("*")
															->find_by(array("nama" => "Perjalanan Dinas Staf", "MONTH(month)" => $month));
		}

		// add to check anggaran available or not
		if($anggaran) {

			// check anggaran_id pada fin_detail_pen"gajuan_dana
			$anggaran_check = $this->Fin_detail_pengajuan_dana_model->select("anggaran_id")
																->join("fin_pengajuan_dana", "fin_pengajuan_dana.id = fin_detail_pengajuan_dana.pengajuan_id")
																->group_by("fin_detail_pengajuan_dana.pengajuan_id")
																->find_by("fin_pengajuan_dana.perdin_id", $idPerdin);
			// apabila nilai anggaran_id pada fin_detail_pengajuan_dana = 0
			if($anggaran_check->anggaran_id == 0) {
				// cek setiap fin_detail_pengajuan_dana.id dengan perdin_id = $id_perdin
				$list_detail_pengajuan_dana = $this->Fin_detail_pengajuan_dana_model->select("fin_detail_pengajuan_dana.id")
																					->join("fin_pengajuan_dana", "fin_pengajuan_dana.id = fin_detail_pengajuan_dana.pengajuan_id")
																					->where("fin_pengajuan_dana.perdin_id", $idPerdin)
																					->find_all();

				// maka update setiap anggaran_id pada fin_detail_pengajuan_dana
				$anggaran_update = array(
					"anggaran_id"	=> $anggaran->id
					);

				foreach($list_detail_pengajuan_dana as $value) {
					$this->Fin_detail_pengajuan_dana_model->update($value->id, $anggaran_update);
				}
			}

			$data["anggaran"] 	= $anggaran;

			// echo json_encode($data["anggaran"]);

			// Realisasi = jika ada fin_detail_pengajuan_dana.status = 0 maka munculkan sum keseluruhan yang 0 kecuali pengajuan saat ini, kalau tidak ada yang status 0 maka munculkan 0
			/*
			old format
			$realisasi	= $this->Fin_detail_pengajuan_dana_model->select("sum(total) AS total")
																		->join("fin_pengajuan_dana", "fin_pengajuan_dana.id = fin_detail_pengajuan_dana.pengajuan_id")
																		->where_not_in("perdin_id", $idPerdin)
																		->find_by(array("anggaran_id" => $anggaran->id, "status" => 0));
																		// ->find_all();
			*/

			// query
			/*
			SELECT fin_pengajuan_dana.id, fin_pengajuan_dana.nama_pengajuan, fin_detail_pengajuan_dana.anggaran_id, SUM(fin_detail_pengajuan_dana.total) AS total, fin_ca.status
			FROM fin_pengajuan_dana
			JOIN fin_detail_pengajuan_dana ON fin_detail_pengajuan_dana.pengajuan_id = fin_pengajuan_dana.id
			JOIN fin_ca ON fin_ca.pengajuan_id = fin_pengajuan_dana.id
			WHERE fin_detail_pengajuan_dana.anggaran_id = 5 AND fin_ca.status = 0
			GROUP BY fin_pengajuan_dana.id
			*/
			// Realisasi format baru = jika ada fin_ca.status = 0 dan 
			// echo json_encode($data["realisasi"]);
			$realisasi = $this->Fin_pengajuan_dana_model->select("fin_pengajuan_dana.id, fin_pengajuan_dana.nama_pengajuan, fin_detail_pengajuan_dana.anggaran_id, fin_ca.nominal_ca, fin_ca.status")
														->join("fin_detail_pengajuan_dana", "fin_detail_pengajuan_dana.pengajuan_id = fin_pengajuan_dana.id")
														->join("fin_ca", "fin_ca.pengajuan_id = fin_pengajuan_dana.id")
														->where(array("fin_detail_pengajuan_dana.anggaran_id" => $anggaran->id, "fin_ca.status" => 0))
														->find_all();

			// calculate all realization
			$nominal_realisasi = 0;
			
			if($realisasi) {
				foreach($realisasi as $value) {
					$nominal_realisasi = $nominal_realisasi+$value->nominal_ca;
				}
			}

			// $data["realisasi"]	= ($realisasi != false) ? $realisasi->total : 0;
			$data["realisasi"]	= ($realisasi != false) ? $nominal_realisasi : 0;

			// echo json_encode($data["realisasi"]);
			// check fin_detail_pengajuan_dana.status = 0
			$fin_detail_pengajuan_dana = $this->Fin_detail_pengajuan_dana_model->select("*")
																						->order_by("id", "desc")
																						->find_by(array("anggaran_id" => $anggaran->id, "status" => 0)); //check detail pengajuan dengan id anggaran saat ini
																						// ->find_by("status", 0);

			// disable for a while, pakai dulu saldo dari fin_anggaran.saldo
			// $data["realisasi"] 	= (!empty($fin_detail_pengajuan_dana) || is_array($fin_detail_pengajuan_dana)) ? ($fin_detail_pengajuan_dana->harga_satuan*$fin_detail_pengajuan_dana->jumlah) : 0;
			// $data["saldo"]		= 
			// end disable
		}
		else {
			$data["anggaran"] = null;
			$data["realisasi"] = null;
		}

		// get pkp for value of form
		$data["pkp"]		= $this->Pkp_model->select("*")
												->order_by('id', 'asc')
												->find_all();
		// get pajak for value of form
		$data['pajak'] 		= $this->Pajak_model->select('pkp.nama, pkp.npwp, pkp.alamat, pajak.pkp_id, pajak.keterangan, pajak.bruto, pajak.dpp, pajak.persentase, pajak.jenis_pajak, pajak.catatan')
											->join('perdin', 'perdin.id = pajak.perdin_id', 'right')
											->join('pkp', 'pajak.pkp_id = pkp.id', 'left')
											->find_by('perdin.id', $idPerdin);

		$data['totalPengajuan'] = $formData['uangdalamkota']+$formData['uangmakan_saku']+$formData['uangtransportasi']+$formData['uangpenginapan'];
		$data['terbilang'] = $this->terbilang($data['totalPengajuan']);
		// echo $totalPengajuan;

		$data['data']['activeMenu']	= "sppd-approvedirkeuangan";   
		$data['title']				= 'Approval Akunting';

		$this->load->view('header');
		$this->load->view('menu',$data);
		$this->load->view('page/sppd_edit_akunting');
		$this->load->view('footer');
	}

	public function popup_pkp() { // triggered from edit_sppd_akunting
		is_logged();

		if($this->input->post('submit')) {
			$data = array(
				"nama"		=> $this->input->post("nama"),
				"npwp"		=> $this->input->post("npwp"),
				"alamat"	=> $this->input->post("alamat")
				);

			if($this->Pkp_model->insert($data)) {
				$perdin['idPerdin'] = $this->input->post("idPerdin");

				// need fix redirect with post data idPerin
				redirect(base_url().'sppd/approve_akunting');
			}
		}

		$data["idPerdin"] = $this->input->post("idPerdin");

		$this->load->view('page/sppd/modal_pkp.php', $data);
	}

	private function terbilang($value) {
	    $value = abs($value);
	    $angka = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
	    $temp = "";

	    if ($value <12) {
	        $temp = " ". $angka[$value];
	    } else if ($value <20) {
	        $temp = $this->terbilang($value - 10). " belas";
	    } else if ($value <100) {
	        $temp = $this->terbilang($value/10)." puluh". $this->terbilang($value % 10);
	    } else if ($value <200) {
	        $temp = " seratus" . $this->terbilang($value - 100);
	    } else if ($value <1000) {
	        $temp = $this->terbilang($value/100) . " ratus" . $this->terbilang($value % 100);
	    } else if ($value <2000) {
	        $temp = " seribu" . $this->terbilang($value - 1000);
	    } else if ($value <1000000) {
	        $temp = $this->terbilang($value/1000) . " ribu" . $this->terbilang($value % 1000);
	    } else if ($value <1000000000) {
	        $temp = $this->terbilang($value/1000000) . " juta" . $this->terbilang($value % 1000000);
	    } else if ($value <1000000000000) {
	        $temp = $this->terbilang($value/1000000000) . " milyar" . $this->terbilang(fmod($value,1000000000));
	    } else if ($value <1000000000000000) {
	        $temp = $this->terbilang($value/1000000000000) . " trilyun" . $this->terbilang(fmod($value,1000000000000));
	    }     
	        return ucwords($temp);
	}

	// AJAX start
	// edit_sppd_akunting
	public function ajax_edit_sppd_akunting() {
		// handle POST data
		$idPerdin	= $this->input->post('idPerdin');
		$key 		= $this->input->post('key');
		$value 		= $this->input->post('value');

		$data = null;

		if($key != null) {
			// classify request
			if($key == "pkp_id") {
				$data = array(
					$key 	=> $value,
					);
			}
			else if($key == "keterangan") {
				$data = array(
					$key	=> $value,
					);
			}
			else if($key == "jenis_pajak") {
				$data = array(
					$key	=> $value,
					);
			}
			else if($key == "bruto") {
				$data = array(
					$key	=> $value,
					);
			}
			else if($key == "dpp") {
				$data = array(
					$key	=> $value,
					);
			}
			else if($key == "bruto") {
				$data = array(
					$key	=> $value,
					);
			}
			else if($key == "persentase") {
				$data = array(
					$key	=> $value,
					);
			}
			else if($key == "catatan") {
				$data = array(
					$key	=> $value,
					);
			}

			// check perdin_id inside pajak
			$id = $this->Pajak_model->find_by("perdin_id", $idPerdin);
			// if found then update
			if($id == true) {
				$this->Pajak_model->update_where("perdin_id", $idPerdin, $data);

				echo "success";
			}
			// else if cannot found create row
			else {
				$data["perdin_id"] = $idPerdin;

				// echo json_encode($data);
				$this->Pajak_model->insert($data);

				echo "success";
			}
		}
	}

	public function modal_akunting() {
		if($this->input->post("approve")) {
			$data = array(
				"status"					=> "Approval Akunting",
				"time_approval_akunting"	=> date("Y-m-d H:i:s"),
				"approval_akunting"			=> $this->session->userdata("user_login"),
				);

			if($this->Perdin_model->update($this->input->post("idPerdin"), $data)) {

				// set notification to creator

				// set notification to keuangan
				$this->sendNotif("Terdapat pengajuan yang harus diapprove oleh Keuangan", $this->getPicId("keuangan"));
				// set success message
				// redirect to hcm page
				redirect(base_url()."sppd/approve_hcm");
			}
		}
		if($this->input->post("reject")) {
			$data = array(
				"status"			=> "Reject Akunting",
				"approval_akunting"	=> $this->session->userdata("user_login"),
				);

			if($this->Perdin_model->update($this->input->post("idPerdin"), $data)) {
				redirect(base_url()."sppd/approve_akunting");
			}
		}

		$data["type"] = $this->input->post("type");
		$data["idPerdin"] = $this->input->post("idPerdin");

		$this->load->view("page/sppd/modal_confirmation", $data);
		// echo $idPerdin;
	}
	// AJAX

	public function approve_budget_control() {
		is_logged();

		$data['data']['dataSppd']	= $this->Sppd_model->get_sppd_budget_control();
		$data['data']['activeMenu']	= "sppd-approve_budget_control";
		//$data['data']['view'] 		= "sppd/approve_accounting";
		$data['title'] 				= "Persetujuan Budget Control";

		$this->load->view('header');
		$this->load->view('menu', $data);
		$this->load->view('page/sppd_approve_budget_control');
		$this->load->view('footer');
	}

	public function modal_budget_control() {
		if($this->input->post("approve")) {
			$data = array(
				"status"						=> "Approval Budget Control",
				"time_approval_budget_control"	=> date("Y-m-d H:i:s"),
				"approval_budget_control"		=> $this->session->userdata("user_login"),
				);

			// if status on perdin updated, then update status in fin_ca
			if($this->Perdin_model->update($this->input->post("idPerdin"), $data)) {
				// start add to ca

				// get pengajuan id of current perdin_id
				$pengajuan = $this->Fin_pengajuan_dana_model->find_by("perdin_id", $this->input->post("idPerdin"));

				$pengajuan_id = ($pengajuan) ? $pengajuan->id : 0;

				// get nominal_ca of current pengajuan
				$nominal = $this->Fin_pengajuan_dana_model->select("SUM(total) AS total")
															->join("fin_detail_pengajuan_dana", "fin_pengajuan_dana.id = fin_detail_pengajuan_dana.pengajuan_id")
															->group_by("fin_detail_pengajuan_dana.pengajuan_id")
															->find_by("fin_detail_pengajuan_dana.pengajuan_id", $pengajuan_id);

				$nominal_ca = ($nominal) ? $nominal->total : 0;

				// insert into fin_ca
				$ca = array(
					"inserted_by"	=> $this->session->userdata("user_login"),
					"pengajuan_id"	=> $pengajuan_id,
					"nominal_ca"	=> $nominal_ca,
					"status"		=> 'open'
					);

				if($this->Fin_ca_model->insert($ca)) {

					// set notification to creator

					// set notification to akunting
					$this->sendNotif("Terdapat pengajuan yang harus diapprove oleh Akunting", $this->getPicId("akunting"));
					// set success message
					// redirect to budget control page
					redirect(base_url()."sppd/approve_budget_control");
				}
				// end ad to ca
				
			}
		}
		if($this->input->post("reject")) {
			$data = array(
				"status"					=> "Reject Budget Control",
				"approval_budget_control"	=> $this->session->userdata("user_login"),
				);

			if($this->Perdin_model->update($this->input->post("idPerdin"), $data)) {
				redirect(base_url()."sppd/approve_budget_control");
			}
		}

		$data["type"] = $this->input->post("type");
		$data["idPerdin"] = $this->input->post("idPerdin");

		$this->load->view("page/sppd/modal_confirmation", $data);
		// echo $idPerdin;
	}

	public function modal_anggaran() {
		is_logged();

		if($this->input->post("submit")) {
			$data = array(
				"inserted_by"	=> $this->session->userdata("user_login"),
				"nama"			=> $this->input->post("nama_anggaran"),
				"nominal"		=> $this->input->post("nominal"),
				"saldo"			=> $this->input->post("saldo"),
				"month"			=> $this->input->post("month"),
				"keterangan"	=> $this->input->post("deskripsi"),
				"fin_coa_id"	=> $this->input->post("coa"),
				);

			if($this->Fin_anggaran_model->insert($data)) {
				redirect(base_url()."sppd/approve_budget_control");
			}
		}

		$data["coa"] = $this->Fin_coa_model->find_all();

		$this->load->view("page/sppd/modal_anggaran", $data);
	}

	public function verifikasi_sppd() {
		$this->load->view("page/sppd/verikasi_sppd");
	}
	
	public function approve_keuangan()
	{ 
		is_logged();
		$data['data']['dataSppd'] = $this->Sppd_model->get_sppd_keuangan();
		$data['data']['activeMenu']	= "sppd-approvekeuangan";
		$data['data']['view'] = "sppd/approve_keuangan";
		$data['title']			=	'Persetujuan Keuangan';
		$this->load->view('header');
		$this->load->view('menu',$data);
		$this->load->view('page/sppd_approve_keuangan');
		$this->load->view('footer');  
	}

	public function popup_keuangan() {
		//is_logged();
		// for approval hcm
		if($this->input->post("approve")) {
			// if approve
			$data = array(
				"status"					=> "Approval Keuangan",
				"time_approval_keuangan"	=> date("Y-m-d H:i:s"),
				"approval_keuangan"			=> $this->session->userdata("user_login")
				);

			if($this->Perdin_model->update($this->input->post("idPerdin"), $data)) {
				// ubah fin_ca dari open menjadi ca

				// ambil id pengajuan_dana
				$pengajuan_id = $this->Fin_pengajuan_dana_model->find_by("perdin_id", $this->input->post("idPerdin"))->id;

				$data = array(
					"status" => "ca"
					);

				// update fin_ca yang memiliki pengajuan_id = $pengajuan_id
				if($this->Fin_ca_model->update_where("pengajuan_id", $pengajuan_id, $data)) {
					redirect(base_url()."sppd/approve_keuangan");
				}
			}
		}
		if($this->input->post("reject")) {
			$data = array(
				"status"					=> "Not Approve Keuangan",
				"time_approval_keuangan"	=> date("Y-m-d H:i:s"),
				"approval_keuangan"			=> $this->session->userdata("user_login")
				);

			if($this->Perdin_model->update($this->input->post("idPerdin"), $data)) {
				redirect(base_url()."sppd/approve_keuangan");
			}
		}

		$data["idPerdin"] = $this->input->post("idPerdin");
		$data["type"] = $this->input->post("type");

		$this->load->view("page/sppd/modal_confirmation", $data);

		// echo json_encode($data["anggaran"]);

	}
	
	public function approve_direktur_keuangan()
	{ 
		is_logged();
		$data['data']['dataSppd'] = $this->Sppd_model->get_sppd_dir_keuangan();
		// $data['data']['activeMenu']	= "sppd-approvekeuangan";
		// $data['data']['view'] = "sppd/approve_keuangan";
		$data['title']			=	'Persetujuan Direktur Keuangan';

		$this->load->view('header');
		$this->load->view('menu',$data);
		$this->load->view('page/sppd_approve_direktur_keuangan');
		$this->load->view('footer');  
	}

	public function popup_direktur_keuangan() {
		//is_logged();
		// for approval hcm
		if($this->input->post("approve")) {
			// if approve
			$data = array(
				"status"							=> "Approval Direktur Keuangan",
				"time_approval_dirkeuangan"	=> date("Y-m-d H:i:s"),
				"approval_dirkeuangan"		=> $this->session->userdata("user_login")
				);

			if($this->Perdin_model->update($this->input->post("idPerdin"), $data)) {
				// ubah fin_ca dari open menjadi ca

				// ambil id pengajuan_dana
				$pengajuan_id = $this->Fin_pengajuan_dana_model->find_by("perdin_id", $this->input->post("idPerdin"))->id;

				$data = array(
					"status" => "ca"
					);

				// update fin_ca yang memiliki pengajuan_id = $pengajuan_id
				if($this->Fin_ca_model->update_where("pengajuan_id", $pengajuan_id, $data)) {
					redirect(base_url()."sppd/approve_keuangan");
				}
			}
		}
		if($this->input->post("reject")) {
			$data = array(
				"status"					=> "Not Approve Direktur Keuangan",
				"time_approval_dirkeuangan"	=> date("Y-m-d H:i:s"),
				"approval_dirkeuangan"		=> $this->session->userdata("user_login")
				);

			if($this->Perdin_model->update($this->input->post("idPerdin"), $data)) {
				redirect(base_url()."sppd/approve_keuangan");
			}
		}

		$data["idPerdin"] = $this->input->post("idPerdin");
		$data["type"] = $this->input->post("type");

		$this->load->view("page/sppd/modal_confirmation", $data);

		// echo json_encode($data["anggaran"]);

	}

	public function approve_direktur_utama() {
		is_logged();

		$data["data"]["dataSppd"]	= $this->Sppd_model->get_sppd_dir_utama();
		$data["title"]				= "Persetujuan Direktur Utama";

		$this->load->view("header", $data);
		$this->load->view("menu");
		$this->load->view("page/sppd_approve_direktur_utama");
		$this->load->view("footer");
	}
	
	public function approve_dirkeuangan()
	{ 
		is_logged();
		$data['data']['dataSppd'] = $this->Sppd_model->get_sppd_dirkeuangan();
		$data['data']['activeMenu']	= "sppd-approvedirkeuangan";
		$data['data']['view'] = "sppd/approve_dirkeuangan";
		$data['title']			=	'Persetujuan Direktur Keuangan';
		$this->load->view('header');
		$this->load->view('menu',$data);
		$this->load->view('page/sppd_approve_dirkeuangan');
		$this->load->view('footer'); 
	}
	
	public function edit_sppd_dirkeuangan(){  
		is_logged();
		$dataPerdin =  $this->Sppd_model->view_perdin_modal();
		$data['dataEdit'] = $dataPerdin;
		$data['data']['activeMenu']	= "sppd-approvedirkeuangan";   
		$data['title']			=	'';
		$this->load->view('header');
		$this->load->view('menu',$data);
		$this->load->view('page/sppd_edit_dirkeuangan');
		$this->load->view('footer'); 
	}

	public function sdm_hapus_peserta() {
		$data = $this->Perdin_peserta_model->find($this->input->post("idPeserta"));

		//echo json_encode($data);
		
		$this->load->view("page/sppd/modal_confirmation_hapus_peserta");
	}
	
	public function approve_pencairan()
	{ 
		/*
		is_logged();
		$data['data']['dataSppd'] = $this->Sppd_model->get_sppd_pencairan();
		$data['data']['activeMenu']	= "sppd-pencairan";
		$data['data']['view'] = "sppd/approve_pencairan";
		
		$data['title']			=	'Pencairan';
		$this->load->view('header');
		$this->load->view('menu',$data);
		$this->load->view('page/sppd_approve_pencairan');
		$this->load->view('footer');
		*/

		$data["data"]["activeMenu"]	= "sppd-pencairan";
		$data["title"]				= "Pencairan";

		$this->load->view("header");
		$this->load->view("menu", $data);
		$this->load->view("page/sppd_approve_pencairan");
		$this->load->view("footer");
	}

	// to show verification data in modal mode for pencairan
	public function modal_pencairan() {
		is_logged();

		if($this->input->post("approve")) {
			$data = array(
				"status"					=> "Approval Pencairan",
				"approval_pencairan"		=> $this->session->userdata("user_login"),
				"time_approval_pencairan"	=> date("Y-m-d H:i:s")
				);

			if($this->Perdin_model->update($this->input->post("idPerdin"), $data)) {
				redirect(base_url()."sppd/approve_pencairan");
			}
		}
		if($this->input->post("reject")) {
			$data = array(
				"status"	=> "Not Approve Pencairan",
				"time_approval_pencairan"	=> date("Y-m-d H:i:s")
				);

			if($this->Perdin_model->update($this->input->post("idPerdin"), $data)) {
				redirect(base_url()."sppd/approve_pencairan");
			}

		}
		$this->load->library('Kripto/Kripto');

		// get a secret message from decrypted message
		$message = $this->kripto->encrypt_decrypt('decrypt',$this->input->post("qr"));

		// if message is true
		if($message) {
			// split them
			$id = explode("-", $message);
			// take a second id
			$id = $id[1];
			// take data from perdin by id
			$data["data"] = $this->Perdin_model->select("perdin.id, perdin.status, perdin.tgl_insert, perdin.user_insert, perdin.tgl_berangkat, perdin.tgl_kembali, perdin.tujuan_kota, perdin.keperluan, perdin.approval_atasan, perdin.approval_adminsdm, perdin.approval_sdm, perdin.approval_akunting, perdin.approval_budget_control, perdin.approval_keuangan, perdin.approval_dirkeuangan, fin_ca.nominal_ca")
												->join("fin_pengajuan_dana", "fin_pengajuan_dana.perdin_id = perdin.id")
												->join("fin_ca", "fin_ca.pengajuan_id = fin_pengajuan_dana.id")
												->find($id);
			// show dialog for popup

			$this->load->view("page/sppd/modal_approve_pencairan", $data);
			// echo json_encode($data); // for debug
		}

	}

	public function clock() {
		echo date("Y-m-d H:i:s");
	}

	public function realisasi_sppd() {
		is_logged();

		$data['data']['dataSppd'] = $this->Sppd_model->get_sppd_pencairan();
		// $data['data']['perdin'] = $this->Perdin_model->where("status", "Approval Pencairan")
														// ->find_all();
		$data['data']['activeMenu']	= "sppd-realisasi";
		// $data['data']['view'] = "sppd/approve_pencairan";
		
		$data['title']			=	'Realisasi SPPD';
		$this->load->view('header');
		$this->load->view('menu', $data);
		$this->load->view('page/sppd_realisasi');
		// echo "Ini realisasi SPPD";
		$this->load->view('footer');
	}

	// public function show_realisasi_sppd($id) { // id perdin
	public function show_realisasi_sppd() { // update to post parameter passing
		if($this->input->post("idPerdin") || $this->input->post("submit")) {

			is_logged();

			if($this->input->post('submit')) {
				/* old format
				$realisasi = $this->input->post('realisasi'); // value of each realized budget
				$realized = $this->input->post('is_realized');
				$budget_account = $this->input->post('budget_account');

				foreach($realisasi as $key => $value) {
					$this->Sppd_model->update_realisasi_sppd($key, $value);
				}

				$this->Sppd_model->update_is_realized($id, $realized);
				$this->Sppd_model->update_budget($id, $budget_account);

				redirect(base_url().'/sppd/realisasi_sppd');
				*/
				$id = $this->input->post("idPerdin");

				$data = array(
					"is_realized" 	=> 1,
					"status"		=> "Realisasi",
					);

				if($this->Perdin_model->update($id, $data)) {
					redirect(base_url()."sppd/realisasi_sppd");
				}
			}
			
			$id = $this->input->post("idPerdin");

			setlocale(LC_MONETARY,"id");
			$data['sppd'] = $this->Sppd_model->get_current_sppd($id);
			$data['rincian'] = $this->Sppd_model->get_current_sppd_rincian($id);
			$data['data']['activeMenu']	= "Data Pengajuan SPPD";
			// $data['budgets'] = $this->Budgets_model->get_budget_temp();

			$data["idPerdin"]		= $id;

			$data['budgets'] = $this->Fin_anggaran_model->select('fin_anggaran.id, no_akun, nama AS nama_akun')
														->join('fin_coa', 'fin_coa.id = fin_anggaran.fin_coa_id')
														->find_all();

			$data["biaya"]	= $this->Perdin_model->select("keperluan, SUM(uangmakan_saku)*jml_hari AS ums, SUM(uangtransportasi) AS ut, SUM(uangpenginapan)*menginap AS up, SUM(uangdalamkota) AS udk")
													->join("perdin_peserta", "perdin.id = perdin_peserta.no_sppd")
													->group_by("perdin_peserta.no_sppd")
													->find($id);

			$realisasi	= $this->Fin_pengajuan_dana_model->select('*')
																	->join("fin_detail_pengajuan_dana", "fin_pengajuan_dana.id = fin_detail_pengajuan_dana.pengajuan_id")
																	->where("fin_pengajuan_dana.perdin_id", $id)
																	->find_all();

			foreach($realisasi as $value) {
				if($value->nama_perkiraan == "Uang Makan Saku") {
					$data["realisasi"]["ums"]["id"] = $value->id;
					$data["realisasi"]["ums"]["value"] = $value->realisasi;
				}
				if($value->nama_perkiraan == "Uang Transportasi") {
					$data["realisasi"]["ut"]["id"] = $value->id;
					$data["realisasi"]["ut"]["value"] = $value->realisasi;
				}
				if($value->nama_perkiraan == "Uang Penginapan") {
					$data["realisasi"]["up"]["id"] = $value->id;
					$data["realisasi"]["up"]["value"] = $value->realisasi;
				}
				if($value->nama_perkiraan == "Uang Dalam Kota") {
					$data["realisasi"]["udk"]["id"] = $value->id;
					$data["realisasi"]["udk"]["value"] = $value->realisasi;
				}
			}

			$data['title']			=	'Realisasi SPPD';
			$this->load->view('header');
			$this->load->view('menu', $data);
			$this->load->view('page/sppd_realisasi_show');
			$this->load->view('footer');
			// echo json_encode($data['budgets']);
		}
		else {
			echo "404";
		}
	}

	public function popup_realisasi() {
		if($this->input->post("submit")) {

		}

		$idPengajuan = $this->input->post("idPengajuan");
		$idPerdin = $this->input->post("idPerdin");

		$this->Fin_pengajuan_dana_model->where("pengajuan_id", $idPengajuan);

		$this->load->view("page/sppd/popup_realisasi");
	}

	public function approve_realisasi() {
		is_logged();

		$data['data']['dataSppd'] = $this->Sppd_model->get_sppd_realisasi();
		
		$data['title']			=	'Persetujuan Realisasi';
		$this->load->view('header', $data);
		$this->load->view('menu');
		$this->load->view("page/sppd_approve_realisasi");
		$this->load->view("footer");
	}

	public function approval_realisasi() {
			
		$id = $this->input->post("idPerdin");

		setlocale(LC_MONETARY,"id");
		$data['sppd'] = $this->Sppd_model->get_current_sppd($id);
		$data['rincian'] = $this->Sppd_model->get_current_sppd_rincian($id);
		$data['data']['activeMenu']	= "sppd-realisasi";
		// $data['budgets'] = $this->Budgets_model->get_budget_temp();

		$data["idPerdin"]		= $id;

		$data['budgets'] = $this->Fin_anggaran_model->select('fin_anggaran.id, no_akun, nama AS nama_akun')
													->join('fin_coa', 'fin_coa.id = fin_anggaran.fin_coa_id')
													->find_all();

		$data["biaya"]	= $this->Perdin_model->select("keperluan, (SUM(uangmakan_saku)*jml_hari) AS ums, SUM(uangtransportasi) AS ut, (SUM(uangpenginapan)*menginap) AS up, SUM(uangdalamkota) AS udk")
												->join("perdin_peserta", "perdin.id = perdin_peserta.no_sppd")
												->group_by("perdin_peserta.no_sppd")
												->find($id);

		$realisasi	= $this->Fin_pengajuan_dana_model->select('*')
																->join("fin_detail_pengajuan_dana", "fin_pengajuan_dana.id = fin_detail_pengajuan_dana.pengajuan_id")
																->where("fin_pengajuan_dana.perdin_id", $id)
																->find_all();

		foreach($realisasi as $value) {
			if($value->nama_perkiraan == "Uang Makan Saku") {
				$data["realisasi"]["ums"]["id"] = $value->id;
				$data["realisasi"]["ums"]["value"] = $value->realisasi;
			}
			if($value->nama_perkiraan == "Uang Transportasi") {
				$data["realisasi"]["ut"]["id"] = $value->id;
				$data["realisasi"]["ut"]["value"] = $value->realisasi;
			}
			if($value->nama_perkiraan == "Uang Penginapan") {
				$data["realisasi"]["up"]["id"] = $value->id;
				$data["realisasi"]["up"]["value"] = $value->realisasi;
			}
			if($value->nama_perkiraan == "Uang Dalam Kota") {
				$data["realisasi"]["udk"]["id"] = $value->id;
				$data["realisasi"]["udk"]["value"] = $value->realisasi;
			}
		}

		$data['title']			=	'Realisasi SPPD';

		$this->load->view('header');
		$this->load->view('menu', $data);
		$this->load->view('page/sppd_approval_realisasi');
		$this->load->view('footer');

	}

	public function popup_approval_realisasi() {
		if($this->input->post("submit")) {
			$id = $this->input->post("idPerdin");

			$data = array(
				"approval_realisasi"		=> $this->session->userdata("user_login"),
				"time_approval_realisasi"	=> date("Y-m-d H:i:s"),
				"status"					=> "Approval Realisasi"
				);

			if($this->Perdin_model->update($id, $data)) {
				redirect(base_url()."sppd/approve_realisasi");
			}

		}

		$data["idPerdin"] = $this->input->post("idPerdin");

		$this->load->view("page/sppd/modal_approval_realisasi", $data);
	}

	// ajax to update realisasi

	public function update_realisasi_sppd() {

		// catch ID detail pengajuan dana
		$id = $this->input->post("idDetailPengajuanDana");

		if($id) {

			// get value to be update
			$data = array(
				"status"	=> "Realisasi",
				"realisasi"	=> $this->input->post("value")
				);

			// update current id with new value
			$this->Fin_detail_pengajuan_dana_model->update($id, $data);
		}
		else {
			show_404();
		}
	}

	// end ajax

	public function approve_ca() {
		is_logged();

		$data['data']['dataSppd'] = $this->Sppd_model->get_sppd_ca();
		// $data['data']['perdin'] = $this->Perdin_model->where("status", "Approval Pencairan")
														// ->find_all();
		$data['data']['activeMenu']	= "sppd-realisasi";
		// $data['data']['view'] = "sppd/approve_pencairan";
		
		$data['title']			=	'Persetujuan CA';
		$this->load->view('header');
		$this->load->view('menu', $data);
		$this->load->view('page/sppd_approve_ca');
		// echo "Ini realisasi SPPD";
		$this->load->view('footer');
	}

	public function approval_ca_new() {
		if($this->input->post("idPerdin") || $this->input->post("submit")) {

			is_logged();

			if($this->input->post('submit')) {
				/* old format
				$realisasi = $this->input->post('realisasi'); // value of each realized budget
				$realized = $this->input->post('is_realized');
				$budget_account = $this->input->post('budget_account');

				foreach($realisasi as $key => $value) {
					$this->Sppd_model->update_realisasi_sppd($key, $value);
				}

				$this->Sppd_model->update_is_realized($id, $realized);
				$this->Sppd_model->update_budget($id, $budget_account);

				redirect(base_url().'/sppd/realisasi_sppd');
				*/
				$id = $this->input->post("idPerdin");

				$data = array(
					"approval_ca"		=> $this->session->userdata("user_login"),
					"time_approval_ca"	=> date("Y-m-d H:i:s"),
					"status"			=> "Approval CA"
					);

				if($this->Perdin_model->update($id, $data)) {
					// update to transaksi
					// ambil id pengajuan
					$pengajuan_id = $this->Fin_pengajuan_dana_model->find_by("perdin_id", $id)->id;
					$detail_pengajuan = $this->Fin_detail_pengajuan_dana_model->select("id, anggaran_id")
																					->where("pengajuan_id", $pengajuan_id)
																					->find_all();
					// update fin_ca

					$data = array(
						"status" => "closed"
						);

					// update fin_ca yang memiliki pengajuan_id = $pengajuan_id
					if($this->Fin_ca_model->update_where("pengajuan_id", $pengajuan_id, $data)) {

						foreach($detail_pengajuan as $value) {

							$anggaran = $this->Fin_anggaran_model->find($value->anggaran_id);
							$decrement = $this->Fin_detail_pengajuan_dana_model->find($value->id);
							$saldo = $anggaran->saldo - $decrement->realisasi;

							$data = array(
								"pengajuan_id"			=> $pengajuan_id,
								"anggaran_id"			=> $value->anggaran_id,
								"detail_pengajuan_id"	=> $value->id,
								"saldo"					=> $saldo
								);

							if($this->Fin_transaksi_model->insert($data)) {

								$data = array(
									"saldo"	=> $saldo
									);
								$this->Fin_anggaran_model->update($value->anggaran_id, $data);
							}
						}

					}


					redirect(base_url()."sppd/approve_ca");
				}
			}
			
			$id = $this->input->post("idPerdin");

			setlocale(LC_MONETARY,"id");
			$data['sppd'] = $this->Sppd_model->get_current_sppd($id);
			$data['rincian'] = $this->Sppd_model->get_current_sppd_rincian($id);
			$data['data']['activeMenu']	= "sppd-realisasi";
			// $data['budgets'] = $this->Budgets_model->get_budget_temp();

			$data["idPerdin"]		= $id;

			$data['budgets'] = $this->Fin_anggaran_model->select('fin_anggaran.id, no_akun, nama AS nama_akun')
														->join('fin_coa', 'fin_coa.id = fin_anggaran.fin_coa_id')
														->find_all();

			$data["biaya"]	= $this->Perdin_model->select("keperluan, (SUM(uangmakan_saku)*jml_hari) AS ums, SUM(uangtransportasi) AS ut, (SUM(uangpenginapan)*menginap) AS up, SUM(uangdalamkota) AS udk")
													->join("perdin_peserta", "perdin.id = perdin_peserta.no_sppd")
													->group_by("perdin_peserta.no_sppd")
													->find($id);

			$realisasi	= $this->Fin_pengajuan_dana_model->select('*')
																	->join("fin_detail_pengajuan_dana", "fin_pengajuan_dana.id = fin_detail_pengajuan_dana.pengajuan_id")
																	->where("fin_pengajuan_dana.perdin_id", $id)
																	->find_all();

			foreach($realisasi as $value) {
				if($value->nama_perkiraan == "Uang Makan Saku") {
					$data["realisasi"]["ums"]["id"] = $value->id;
					$data["realisasi"]["ums"]["value"] = $value->realisasi;
				}
				if($value->nama_perkiraan == "Uang Transportasi") {
					$data["realisasi"]["ut"]["id"] = $value->id;
					$data["realisasi"]["ut"]["value"] = $value->realisasi;
				}
				if($value->nama_perkiraan == "Uang Penginapan") {
					$data["realisasi"]["up"]["id"] = $value->id;
					$data["realisasi"]["up"]["value"] = $value->realisasi;
				}
				if($value->nama_perkiraan == "Uang Dalam Kota") {
					$data["realisasi"]["udk"]["id"] = $value->id;
					$data["realisasi"]["udk"]["value"] = $value->realisasi;
				}
			}

			$data['title']			=	'Realisasi SPPD';
			$this->load->view('header');
			$this->load->view('menu', $data);
			$this->load->view('page/sppd_edit_ca');
			$this->load->view('footer');
			// echo json_encode($data['budgets']);
		}
		else {
			echo "404";
		}
	}
	
	public function cash_advance()
	{ 
		is_logged();
		$data['data']['dataSppd'] = $this->Sppd_model->get_sppd_cashadvance();
		$data['data']['activeMenu']	= "sppd-cashadvance";
		$data['data']['view'] = "sppd/cash_advance";
		$data['title']			=	'Cash Advance';
		$this->load->view('header');
		$this->load->view('menu',$data);
		$this->load->view('page/sppd_cash_advance');
		$this->load->view('footer'); 
	}

	// Laporan Anggaran Start

	public function laporan_anggaran() {
		$data["title"]	= "Laporan Anggaran";

		$condition = array(
			"MONTH(perdin.tgl_insert)"	=> 6,
			"fin_ca.status"				=> "ca"
			);

		$this->load->view("header");
		$this->load->view("menu", $data);
		$this->load->view("page/sppd_laporan_anggaran");
		$this->load->view("footer");
	}

	public function get_laporan_anggaran() {
		if($this->input->post("submit")) {
			/*
			SELECT perdin.id AS perdin_id, perdin.keperluan, perdin.status AS perdin_status, fin_ca.nominal_ca, fin_ca.status, fin_anggaran.nama
			FROM perdin
			JOIN fin_pengajuan_dana ON fin_pengajuan_dana.perdin_id = perdin.id
			JOIN fin_detail_pengajuan_dana ON fin_pengajuan_dana.id = fin_detail_pengajuan_dana.pengajuan_id
			JOIN fin_ca ON fin_ca.pengajuan_id = fin_pengajuan_dana.id
			JOIN fin_anggaran ON fin_detail_pengajuan_dana.anggaran_id = fin_anggaran.id
			WHERE MONTH(tgl_insert) = 06
			GROUP BY fin_pengajuan_dana.id
			ORDER BY fin_anggaran.nama
			*/

			$condition = array(
					"MONTH(perdin.tgl_insert)"	=> substr($this->input->post("month"), 0, 2),
					"fin_ca.status"				=> "ca"
				);

			$data["month"] = date(strtotime($this->input->post("month")));
			$data["year"] = date(strtotime($this->input->post("month")));

			$data["anggaran"]	= $this->Perdin_model->select("perdin.id AS perdin_id, perdin.keperluan, perdin.user_insert, perdin.status AS perdin_status, perdin.tgl_insert, perdin.tgl_berangkat, perdin.tgl_kembali, fin_ca.nominal_ca, fin_ca.status, fin_anggaran.nama, perdin.tujuan_kota")
														->join("fin_pengajuan_dana", "fin_pengajuan_dana.perdin_id = perdin.id")
														->join("fin_detail_pengajuan_dana", "fin_detail_pengajuan_dana.pengajuan_id = fin_pengajuan_dana.id")
														->join("fin_ca", "fin_ca.pengajuan_id = fin_pengajuan_dana.id")
														->join("fin_anggaran", "fin_anggaran.id = fin_detail_pengajuan_dana.anggaran_id")
														->where($condition)
														->group_by("fin_pengajuan_dana.id")
														->order_by("fin_anggaran.nama")
														->find_all();

			// echo json_encode($data["anggaran"]);

			$this->load->view("page/sppd/export_laporan_anggaran", $data);
		}
	}

	public function get_anggaran_month_report($month, $year) {

		if(strlen($month)<2) {
			$month = '0'.$month;
		}
		$data['month'] = $month;
		$data['year'] = $year;

		$data["data"]	= null;
		/* query
		$data['data'] = $this->Surat_model->select('*')
											// ->where('MONTH(surat.tgl_insert)', $month)
											->where('bulan', $month)
											->where('tahun', $year)
											// ->order_by('surat.tgl_insert', 'desc')
											->order_by('id', 'asc') //lebih rasional
											->find_all();
		*/
		$data["data"] = $this->Fin_transaksi_model->select("*")
													->join("fin_pengajuan_dana", "fin_pengajuan_dana.id = fin_transaksi.pengajuan_id")
													->join("perdin", "perdin.id = fin_pengajuan_dana.perdin_id")
													->find_all();

		$this->load->view('page/sppd/export_laporan_anggaran', $data);
	}

	public function anggaran_sppd() {
		// $data["title"]	= "Anggaran SPPD";

		//

		if($this->input->post('submit')) {
			// print_r($_POST);
			$file = $this->input->post('file');

			$dateInfo = date("Y-m-d", strtotime("01-".$this->input->post("bulan")));
			$month = date("m", strtotime($dateInfo));

			$data = array(
				"inserted_by"	=> $this->session->userdata("user_login"),
				'fin_coa_id'	=> $this->input->post('coa'),
				'nama'			=> $this->input->post('nama_anggaran'),
				'keterangan'	=> $this->input->post('deskripsi'),
				'nominal'		=> $this->input->post('nominal'),
				'saldo'			=> $this->input->post('saldo'),
				'month'			=> $dateInfo
				);

			// redirect($dateInfo);

			// cek if bulan is exist or not?
			$checkMonth = $this->Fin_anggaran_model->select("*")
													->where("")
													->find_by(array("nama" => $this->input->post("nama_anggaran"), "MONTH(month)" => $month));

			if($checkMonth) {
				redirect(current_url()."?status=error"); // redirect if failed
			}
			else {
				if($this->Fin_anggaran_model->insert($data)) {
					redirect(current_url()."?status=success"); // redirect if succeed
				}
				else {
					redirect(current_url()."?status=fail"); // redirect if failed
				}
			}

			/*

			echo json_encode($data);
			*/
		}

		$namaAnggaran = array(
			"Perjalanan Dinas Staf",
			"Perjalanan Dinas Direksi"
			);

		$data['title']	= 'Anggaran SPPD';
		$data['coa']	= $this->Fin_coa_model->find_all();
		$data['data']	= $this->Fin_anggaran_model->select('fin_anggaran.id, fin_coa.no_akun, fin_coa.akun, fin_anggaran.nama, fin_anggaran.nominal, fin_anggaran.keterangan')
								->join('fin_coa', 'fin_coa.id = fin_anggaran.fin_coa_id')
								->where_in("fin_anggaran.nama", $namaAnggaran)
								->order_by("month", "desc")
								->order_by("nama", "asc")
								->find_all();

		// echo json_encode($data);

		$this->load->view('header', $data);
		$this->load->view('menu');
		$this->load->view('page/sppd_anggaran');
		$this->load->view('simple_footer');

		//

		// $this->load->view("header");
		// $this->load->view("menu", $data);
		// $this->load->view("page/sppd_anggaran");
		// $this->load->view("footer");
	}

	public function popup_anggaran_sppd() {
		/*

		if($this->input->post("approve")) {
			$data = array(
				"status"					=> "Approval Akunting",
				"time_approval_akunting"	=> date("Y-m-d H:i:s"),
				"approval_akunting"			=> $this->session->userdata("user_login"),
				);

			if($this->Perdin_model->update($this->input->post("idPerdin"), $data)) {
				redirect(base_url()."sppd/approve_akunting");
			}
		}
		if($this->input->post("reject")) {
			$data = array(
				"status"			=> "Reject Akunting",
				"approval_akunting"	=> $this->session->userdata("user_login"),
				);

			if($this->Perdin_model->update($this->input->post("idPerdin"), $data)) {
				redirect(base_url()."sppd/approve_akunting");
			}
		}

		$data["type"] = $this->input->post("type");
		$data["idPerdin"] = $this->input->post("idPerdin");

		$this->load->view("page/sppd/modal_confirmation", $data);
		*/

		/*
		is_logged();

		// if request come from edit popup
		if($this->input->post("edit")) {
			$data = array(
				"nominal"	=> $this->input->post("nominal"),
				"saldo"		=> $this->input->post("saldo"),
				"deskripsi"	=> $this->input->post("deskripsi")
				);

			if($this->Fin_anggaran_model->update($this->input->post("idAnggaran"), $data)) {
				redirect(base_url()."sppd/anggaran_sppd?status=edited");
			}

		}
		// if request come from delete popup
		if($this->input->post("delete")) {
			if($this->Fin_anggaran_model->delete($this->input->post("idAnggaran")) {
				redirect(base_url()."sppd/anggaran_sppd?status=deleted");
			}
		}

		$data["type"]		= $this->input->post("type"); // get type of popup (edit/delete)
		$data["idAnggaran"]	= $this->input->post("idAnggaran"); // get id of anggaran

		$this->load->view("page/sppd/popup_anggaran"); // default page for popup_anggaran
		*/
	}
	
	public function file()
	{
		is_logged();
		$data['data']	=	$this->Sppd_model->view_perdin_modal();
		$data['atasan']	=	$this->Model_user->get_struktur(array('user_name' => $data['data']['dataPerdin']['approval_atasan']));
		$data['pengaju']	=	$this->Model_user->get_struktur(array('user_name' => $data['data']['dataPerdin']['user_insert']));	
		$data['qr']			= $this->generateQR('sppd-'.$this->input->post('idPerdin'));
		// echo json_encode($data["data"]);
		$this->load->view('page/file_fpdf',$data);
	}
	
	public function formulir()
	{
		is_logged();

		$dataPerdin = $this->dataPerdin($this->input->post("idPerdin"));

		$totalPengajuan = $dataPerdin->ums + $dataPerdin->ut + $dataPerdin->up + $dataPerdin->udk;

		if(($totalPengajuan <= 10000000) || ($totalPengajuan > 10000000 && $dataPerdin->status == "Approval Direktur Keuangan") || $totalPengajuan > 250000000 && $dataPerdin->status == "Approval Direktur Utama" || $dataPerdin->status == "Approval Pencairan") { // added to separate formulir based on total of pengajuan
			$data['data']		= $this->Sppd_model->view_perdin_fpd();
			$data['jabatan']	= $this->Model_user->get_struktur(array('user_name' => $data['data']['user_insert'] ));
			$data['qr']			= $this->generateQR('sppd-'.$this->input->post('idPerdin'));

			// Get data anggaran
			$data["anggaran"]	= $this->Fin_pengajuan_dana_model->join("fin_detail_pengajuan_dana", "fin_detail_pengajuan_dana.pengajuan_id = fin_pengajuan_dana.id")
																	->join("fin_anggaran", "fin_detail_pengajuan_dana.anggaran_id = fin_anggaran.id")
																	->group_by("fin_detail_pengajuan_dana.anggaran_id")
																	->find_by("perdin_id", $this->input->post("idPerdin"));
			
			$perdin = $data["data"];
			$totalPengajuan = $perdin['uangdalamkota']+$perdin['uangpenginapan']+$perdin['uangtransportasi']+$perdin['uangmakan_saku'];
			$data['terbilang'] = $this->terbilang($totalPengajuan);
			// ambil
			// $data['terbilang'] = $this->terbilang($data['totalPengajuan']);
			// $data['keuangan']	=	$this->Model_email->get_data(null,null,array('akses_menu' => 'Persetujuan Keuangan'));
			// $data['dirkeuangan']	=	$this->Model_email->get_data(null,null,array('akses_menu' => 'Persetujuan Direktur Keuangan'));
			
			// echo $this->input->post("idPerdin");
			// echo json_encode($data["anggaran"]);
			$this->load->view('page/formulir_fpdf',$data);

			// echo json_encode($data);

		}
		else {
			if($totalPengajuan > 10000000) {
				// echo json_encode($dataPerdin);
				echo "Total pengajuan diatas Rp. 10.000.000, pengajuan harus diapprove direktur keuangan terlebih dahulu";
			}
			else if($totalPengajuan > 250000000) {
				echo "Total pengajuan diatas Rp. 250.000.000, pengajuan harus diapprove direktur utama terlebih dahulu";
			}
		}
	}

	public function get_realisasi($id) { // anggaran_id

		// jika dari ca
		// join fin_pengajuan_dana.id = fin_ca.pengajuan_id
		// join fin_detail_pengajuan_dana.pengajuan_id = fin_pengajuan_dana.id
		// join fin_anggaran.id = fin_detail_pengajuan_dana.anggaran_id
		// where fin_anggaran.id = ? AND fin_ca.status != realisasi

		// jika dari anggaran
		// join 
		$data = $this->Fin_ca_model->select("SUM(fin_ca.nominal_ca) AS realisasi")
									->join("fin_pengajuan_dana", "fin_pengajuan_dana.id = fin_ca.pengajuan_id")
									->find_by(array("fin_pengajuan_danafin_ca.status !=" => "realisasi")); // berarti untuk yang statusnya open dan ca akan dikalkulasikan	

		echo json_encode($data);
	}

	private function generateQR($data) {
		if($data != null) {
			// load library kripto dan qr
			$this->load->library('Kripto/Kripto');
			$this->load->library('Qrcode/Qrcode');
			$this->load->library('encryption');


			// $this->encryption->create_key(16);

			// enkrip data
			// $encrypt = $this->kripto->fnEncrypt($data);
			// $encrypt = $this->kripto->encrypt($data);
			$encrypt = $this->kripto->encrypt_decrypt('encrypt',$data);
			// dekrip data
			// $decrypt = $this->kripto->decrypt($encrypt);
			$decrypt = $this->kripto->encrypt_decrypt('decrypt',$encrypt);

			echo "Input : ".$data."<br/>";
			echo "Encrypt : ".$encrypt."<br/>";
			echo "Decrypt : ".$decrypt."<br/>";

			$params['data'] = $encrypt;
			$params['level'] = '4';
			$params['size'] = 10;
			$params['savename'] = "assets/temp/qr/".$encrypt.".png";

			if($this->qrcode->generate($params)) {
				return $params['savename'];
			}
			else {
				return false;
			}
		}
		else {
			show_404();
		}
	}

	public function sppdValidation() {
		$this->load->library('Kripto/Kripto');

		$encrypted = $this->input->post('data');
		if($encrypted != null) {
			$data = $this->kripto->encrypt_decrypt('decrypt',$encrypted);

			echo $data;

			$id = explode('-',$data);
			$id = $id[1];

			$sppd = $this->Perdin_model->find($id);

			echo json_encode($sppd);
		}
		else {
			show_404();
		}
	}

	private function is_director($id = null) { // function to check director or commissioner are exist or not? based on jabatan

		$return = false;

		$data = $this->Perdin_model->select("users.jabatan")
									->join("perdin_peserta", "perdin_peserta.no_sppd = perdin.id")
									->join("users", "users.user_name = perdin_peserta.nama")
									->where("perdin.id", $id)
									->find_all();

		foreach($data as $value) {
			if($value->jabatan == "Direktur Utama" || $value->jabatan == "Direksi Komisaris") {
				$return = true;
				break;
			}
		}

		return $return;
	}

	// to decrypt encryption message
	private function decryptor($qr) {
		// to decrypt qr id
		$data = $this->kripto->encrypt_decrypt("decrypt", $qr);

		return $data;
	}
	
	public function laporan()
	{
		is_logged();

		//echo json_encode($_POST);
		$dataPerdin			= $this->Sppd_model->view_perdin_modal();
		$data["data"]		= $dataPerdin;

		$userData = $dataPerdin["dataPerdin"];

		$data["pengaju"]	= $this->Users_model->select("*")
												->join("struktur", "struktur.id = users.id_struktur", "left")
												->find_by("user_name", $dataPerdin["dataPerdin"]["user_insert"]);

												echo json_encode($userData);

		echo json_encode($data["pengaju"]);
		/*
		$data['pengaju']	=	$this->Model_user->get_struktur(array('user_name' => $this->session->userdata('user_login')));
		
		echo json_encode($data['pengaju']);
		echo json_encode($data['data']);
		*/

		$this->load->view('page/laporan_fpdf',$data);
		// echo json_encode($data["data"]);
	}
	//========================================= AJAX CODE ================================================================
	public function update_negara_provinsi(){
		is_logged();
		$tujuan 	= $this->input->post('tujuan');
		$data 		= $this->Sppd_model->get_negara_provinsi($tujuan);
		
		echo json_encode($data);
	}
	
	public function update_kota_kab(){
		is_logged();
		$negaraPropinsi 	= $this->input->post('negaraPropinsi');
		$data 		= $this->Sppd_model->get_kota_kab($negaraPropinsi);
		echo json_encode($data);
	}
	
	public function set_peserta(){ 
		is_logged();
		//print_r($this->Sppd_model->biaya_domestik($this->input->post('negaraPropinsi'),$user));
		$data		= $this->Sppd_model->set_peserta();
		
		echo json_encode($data); 
	}
	
	public function gets()
	{
		is_logged();
	//	print_r($this->Model_user->get_kode_struktur_atasan());	
		date_default_timezone_set('Asia/Jakarta');
		echo date('Y-m-d H:i:s');
	}
	
	public function convertroman($num)
	{
		$value	= '';
		
		$roman	=	array(
		'X'		=> 10,
		'IX'	=> 9,
		'V'		=> 5,
		'IV'	=> 4,
		'I'		=> 1
		);
		
		foreach($roman as $val_roma => $number)
		{
			$match	=	intval(intval($num) / $number);
			$value   .=	str_repeat($val_roma,$match);
			
			$num	=	$num % $number;
		}
		
		return $value;
	}
	
	public function save_resume()
	{
		is_logged();
		$this->Sppd_model->save_resume();
		redirect('Sppd/data_sppd');
	}
	
	public function value_assign($array)
	{
		$data	=	$this->Sppd_model->view_perdin_fpd($array['key']);
		
		$url	=	"http://bijb.co.id/sppd/mail.php";
		
		$fields = array(
			'atasan'	=> urlencode($array['atasan']),
			'key'		=> urlencode($array['key']),
			'to'		=> urlencode($array['to']),
			'stat'		=> urlencode($array['stat']),
			'pengaju'	=> urlencode($array['pengaju']),
			'subject'	=> urlencode($array['subject']),
			'menginap'	=> urlencode($data['menginap']),
			'tgl_berangkat'	=> urlencode($data['tgl_berangkat']),
			'jml_hari'	=> urlencode($data['jml_hari']),
			'uangmakan_saku'	=> urlencode($data['uangmakan_saku']),
			'uangdalam_kota'	=> urlencode($data['uangdalamkota']),
			'uangtransportasi'	=> urlencode($data['uangtransportasi']),
			'uangpenginapan'	=> urlencode($data['uangpenginapan']),
			'jml_peserta'	=> urlencode($data['jml_peserta'])
			
		);
		$fields_string = '';
		foreach($fields  as $key => $value){$fields_string .= $key.'='.$value.'&'; }
		rtrim($fields_string);
		
		$ch		= curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_POST,count($fields));
		curl_setopt($ch, CURLOPT_POSTFIELDS,$fields_string);
		
		$result = curl_exec($ch);
		curl_close($ch);
		
		
		
	}
	
	public function value_approve($array)
	{
		$data	=	$this->Sppd_model->view_perdin_fpd($array['key']);
		
		$url	=	"http://bijb.co.id/sppd/mail.php";
		
		$fields = array(
			'atasan'	=> urlencode($array['atasan']),
			'key'		=> urlencode($array['key']),
			'to'		=> urlencode($array['to']),
			'stat'		=> urlencode($array['stat']),
			'pengaju'	=> urlencode($array['pengaju']),
			'subject'	=> urlencode($array['subject']),
			'menginap'	=> urlencode($data['menginap']),
			'tgl_berangkat'	=> urlencode($data['tgl_berangkat']),
			'jml_hari'	=> urlencode($data['jml_hari']),
			'uangmakan_saku'	=> urlencode($data['uangmakan_saku']),
			'uangdalam_kota'	=> urlencode($data['uangdalamkota']),
			'uangtransportasi'	=> urlencode($data['uangtransportasi']),
			'uangpenginapan'	=> urlencode($data['uangpenginapan']),
			'jml_peserta'	=> urlencode($data['jml_peserta']),
			'app'	=> false
			
		);
		
		return $fields;
	}
	public function save_sppd(){ 
		is_logged();
	//	print_r($this->input->post());
	//echo 'aa';
		$dari	= $this->Model_user->get_kode_struktur_atasan();
		$tgl	= date('Y-m-d H:i:s');
		$romawi	= $this->convertroman(date('m',strtotime($tgl)));
		$data 	= array(
			'tipe_surat'	=>	'internal',
			'nama_surat'	=>	$this->input->post('keperluan'),
			'jenis_surat'	=>	'SPPD',
			// 'nomor_surat'	=>	$this->Model_surat->supp_generatenum('SPPD',date('m',strtotime($tgl)),date('Y',strtotime($tgl)),strtolower('surat')).'/SPPD-'.$dari->kode_struktur.'/BIJB/'.$romawi.'/'.date('Y',strtotime($tgl)),
			'nomor_surat'	=>  '  /SPPD-         /BIJB/'.$romawi.'/'.date('Y',strtotime($tgl)), // added to dynamic manual input on form
			'tujuan'		=>	$this->session->userdata('user_login'),
			'dari'			=>	$dari->kode_struktur,
			'tgl_surat'		=>	$tgl,
			'keterangan'	=>	'',
			'bulan'			=>	date('m',strtotime($tgl)),
			'tahun'			=>	date('Y',strtotime($tgl))
		);

		/*
		disable insert to surat table
		$this->Model_surat->add_suratkeluar($data,strtolower('surat')); 
		*/
		$data	= $this->Sppd_model->save_sppd($data['nomor_surat']);
		
		$dari	= $this->Model_user->get_kode_struktur_atasan();
		
		/*$array 	= array(
			'atasan'	=> $dari->user_name,
			'key'		=> $data['perdin'],
			'to'		=> $dari->email,
			'stat'		=> 'atasan',
			'pengaju'	=> $this->session->userdata('user_login'),
			'subject'	=> 'Approve SPPD Atasan'
		);*/
	//	echo $this->value_assign($array);

		/*
		$idAtasan = $this->Users_model->find_by("user_name", $this->session->userdata("user_login"))->sppd_atasan;
		$message = "Ada pengajuan SPPD yang harus disetujui";

		$this->sendNotification($id, $message);
		*/
		
		echo json_encode($data); 
	}
	
	public function view_perdin_modal(){ 
		$data		= $this->Sppd_model->view_perdin_modal();
		echo json_encode($data); 
	}
	
	public function view_perdin_fpd(){
		$data		= $this->Sppd_model->view_perdin_fpd();
		echo json_encode($data); 
	}
	
	public function approval_atasan(){ 
		$perdin	= $this->Sppd_model->get_pengajuperdin($this->input->post('idPerdin'));
		
	//	$email	= $this->Model_email->get_data(null,null,array('akses_menu' => 'Persetujuan Personalia'));
	
		$data		= $this->Sppd_model->approval_atasan();
		
		foreach($email as $row){};		
		

		$this->value_assign($array);
		
		echo json_encode($data); 
	}
	
	public function reject_atasan()
	{
		$data		= $this->Sppd_model->reject_atasan();
		echo json_encode($data);
	}
	
	
	public function app()
	{
		$url = '';
		switch($this->input->get('stat'))
		{
				
			case('atasan'):
				$data	= $this->Sppd_model->approval_atasan('app');
			//	$email	= $this->Model_email->get_data(null,null,array('akses_menu' => 'Persetujuan Personalia'));
				
				$url	 = 'sdm';
				$sub	 = 'Approve SPPD Personalia';

			break;
			case('sdm'):
				$data		= $this->Sppd_model->approval_hcm('app');
			//	$email	= $this->Model_email->get_data(null,null,array('akses_menu' => 'Persetujuan HCM'));

				$url	 = 'hcm';
				$sub	 = 'Approve SPPD HCM';				
				
			break;
			case('hcm'):
				$data		= $this->Sppd_model->approval_hcm('app');
			//	$email	= $this->Model_email->get_data(null,null,array('akses_menu' => 'Persetujuan Keuangan'));

				$url	 = 'sdm';
				$sub	 = 'Approve SPPD keuangan';				
				
			break;
			case('keuangan'):
				$data		= $this->Sppd_model->approval_keuangan('app');
			//	$email	= $this->Model_email->get_data(null,null,array('akses_menu' => 'Persetujuan Direktur Keuangan'));

				$url	 = 'dirkeuangan';
				$sub	 = 'Approve SPPD Dir Keuangan';
				
			break;
			case('dirkeuangan'):
				$data		= $this->Sppd_model->approval_dirkeuangan('app');
		//		$email	= $this->Model_email->get_data(null,null,array('akses_menu' => 'Pencairan'));

				$url	 = 'pencairan';
				$sub	 = 'Approve SPPD Pencairan';
				
			break;
			case('pencairan'):
				$data		= $this->Sppd_model->approval_pencairan('app');
			//	$email	= $this->Model_email->get_data(null,null,array('akses_menu' => 'Cash Advance'));

				$url	 = 'ca';
				$sub	 = 'Approve SPPD CA';
				
			break;
			case('ca'):
				$data		= $this->Sppd_model->approval_ca('app');			
			break;
		}
		

		if($url != '')
		{
			$perdin	= $this->Sppd_model->get_pengajuperdin($this->input->get('key'));
			
			echo json_encode($this->value_approve($array));
		}
		
		
	}
	
	public function approval_hcm(){ 
		is_logged();
		
		$perdin	= $this->Sppd_model->get_pengajuperdin($this->input->post('idPerdin'));
		
	//	$email	= $this->Model_email->get_data(null,null,array('akses_menu' => 'Persetujuan Keuangan'));
				
		$data		= $this->Sppd_model->approval_hcm();
		
		// get data perdin
		$dataPerdin = $this->Perdin_model->select("perdin.tgl_insert, perdin.user_insert, keperluan, jml_hari, menginap, SUM(uangmakan_saku) AS ums, SUM(uangtransportasi) AS ut, SUM(uangpenginapan) AS up, SUM(uangdalamkota) AS udk")
											->join("perdin_peserta", "perdin_peserta.no_sppd = perdin.id")
											->find($this->input->post("idPerdin"));

		// echo json_encode($dataPerdin);

		// remove if id perdin exist inside table fin_pengajuan_dana and fin_detail_pengajuan_dana
		if($this->Fin_pengajuan_dana_model->find_by("perdin_id", $this->input->post("idPerdin"))) {
			$this->Fin_pengajuan_dana_model->delete_where(array("fin_pengajuan_dana.perdin_id" => $this->input->post("idPerdin")));
		}

		// insert to pengajuan
		$data = array(
			"inserted_by"		=> $this->session->userdata("user_login"),
			"tgl_pengajuan"		=> $dataPerdin->tgl_insert,
			"tgl_penggunaan"	=> date("Y-m-d"),
			"nama_pengaju"		=> $dataPerdin->user_insert,
			"nama_pengajuan"	=> $dataPerdin->keperluan,
			"perdin_id"			=> $this->input->post("idPerdin")
			);

		$pengajuan = $this->Fin_pengajuan_dana_model->insert($data); //

		// get anggaran id by name pengajuan dana and current month
		$month 				= date("m"); // get month for month filtering
		$anggaran			= $this->Fin_anggaran_model->select("*")
														->find_by(array("nama" => "Perjalanan Dinas", "MONTH(month)" => $month));

		$anggaran_id		= ($anggaran != false) ? $anggaran->id : "0";

		$harga_satuan = (($dataPerdin->ums*$dataPerdin->jml_hari) + $dataPerdin->ut + ($dataPerdin->up * $dataPerdin->menginap) + $dataPerdin->udk); //static for a while, when i think about total

		// gather perdin_peserta data added at 31032017
		$data2 = array(
			array( // Uang Makan Saku
				"pengajuan_id"		=> $pengajuan,
				"inserted_by"		=> $this->session->userdata("user_login"),
				"nama_perkiraan"	=> "Uang Makan Saku",
				"harga_satuan"		=> $dataPerdin->ums,
				"anggaran_id"		=> $anggaran_id,
				"jumlah"			=> 1,
				"total"				=> ($dataPerdin->ums*$dataPerdin->jml_hari),
				"realisasi"			=> ($dataPerdin->ums*$dataPerdin->jml_hari)
				),
			array( // Uang Transportasi
				"pengajuan_id"		=> $pengajuan,
				"inserted_by"		=> $this->session->userdata("user_login"),
				"nama_perkiraan"	=> "Uang Transportasi",
				"harga_satuan"		=> $dataPerdin->ut,
				"anggaran_id"		=> $anggaran_id,
				"jumlah"			=> 1,
				"total"				=> $dataPerdin->ut*1,
				"realisasi"			=> $dataPerdin->ut*1
				),
			array( // Uang Penginapan
				"pengajuan_id"		=> $pengajuan,
				"inserted_by"		=> $this->session->userdata("user_login"),
				"nama_perkiraan"	=> "Uang Penginapan",
				"harga_satuan"		=> ($dataPerdin->up * $dataPerdin->menginap),
				"anggaran_id"		=> $anggaran_id,
				"jumlah"			=> 1,
				"total"				=> ($dataPerdin->up * $dataPerdin->menginap)*1,
				"realisasi"			=> ($dataPerdin->up * $dataPerdin->menginap)*1
				),
			array( // Uang Dalam Kota
				"pengajuan_id"		=> $pengajuan,
				"inserted_by"		=> $this->session->userdata("user_login"),
				"nama_perkiraan"	=> "Uang Dalam Kota",
				"harga_satuan"		=> $dataPerdin->udk,
				"anggaran_id"		=> $anggaran_id,
				"jumlah"			=> 1,
				"total"				=> $dataPerdin->udk*1,
				"realisasi"			=> $dataPerdin->udk*1
				)
			);

		// $this->db->insert_batch("fin_detail_pengajuan_dana", $data2);
		$this->Fin_detail_pengajuan_dana_model->insert_batch($data2);

		/* disabled due change of detail, use component of perdin_peserta 31032017
		$data2 = array(
			"pengajuan_id"		=> $pengajuan,
			"inserted_by"		=> $this->session->userdata("user_login"),
			"nama_perkiraan"	=> $dataPerdin->keperluan,
			"harga_satuan"		=> $harga_satuan, 
			"anggaran_id"		=> $anggaran_id,
			"jumlah"			=> 1, // static, because pengajuan only one
			"total"				=> $harga_satuan*1
			);

		$this->Fin_detail_pengajuan_dana_model->insert($data2);
		// end insert to pengajuan
		*/

		$this->value_assign($array);
		
		echo json_encode($data); 
	}

	public function approval_akunting() {
		
		$perdin	= $this->Sppd_model->get_pengajuperdin($this->input->post('idPerdin'));

		$data 	= $this->Sppd_model->approval_akunting();

		echo $perdin;

	}

	public function approval_budget_control() {
		
		$perdin	= $this->Sppd_model->get_pengajuperdin($this->input->post('idPerdin'));

		$data 	= $this->Sppd_model->approval_budget_control();

		echo $perdin;

	}
	
	public function reject_hcm()
	{
		$data		= $this->Sppd_model->approval_sdm(null,'Not Approve HCM');
		echo json_encode($data);
	}
	
	public function reject_sdm()
	{
		$data		= $this->Sppd_model->approval_sdm(null,'Not Approve Admin Personalia');
		echo json_encode($data);
	}
	
	
	public function approval_sdm(){ 
		
		$perdin	= $this->Sppd_model->get_pengajuperdin($this->input->post('idPerdin'));
		
	//	$email	= $this->Model_email->get_data(null,null,array('akses_menu' => 'Persetujuan HCM'));
		
		
		$data		= $this->Sppd_model->approval_sdm();
		
		
		//$this->value_assign($array);
		echo json_encode($data); 
	}
	
	public function approval_keuangan(){ 
		is_logged();

		$perdin	= $this->Sppd_model->get_pengajuperdin($this->input->post('idPerdin'));
		
		//	$email	= $this->Model_email->get_data(null,null,array('akses_menu' => 'Persetujuan Direktur Keuangan'));

		/* disabled because of changing procedure 13 April 2017 and move to modal_budget_control
		// start add to ca

		// get pengajuan id of current perdin_id
		$pengajuan = $this->Fin_pengajuan_dana_model->find_by("perdin_id", $this->input->post("idPerdin"));

		$pengajuan_id = ($pengajuan) ? $pengajuan->id : 0;

		// get nominal_ca of current pengajuan
		$nominal = $this->Fin_pengajuan_dana_model->select("SUM(total) AS total")
													->join("fin_detail_pengajuan_dana", "fin_pengajuan_dana.id = fin_detail_pengajuan_dana.pengajuan_id")
													->group_by("fin_detail_pengajuan_dana.pengajuan_id")
													->find_by("fin_detail_pengajuan_dana.pengajuan_id", $pengajuan_id);

		$nominal_ca = ($nominal) ? $nominal->total : 0;

		// insert into fin_ca
		$ca = array(
			"inserted_by"	=> $this->session->userdata("user_login"),
			"pengajuan_id"	=> $pengajuan_id,
			"nominal_ca"	=> $nominal_ca,
			"status"		=> 0
			);

		$this->Fin_ca_model->insert($ca);
		// end ad to ca
			
		*/
		$data		= $this->Sppd_model->approval_keuangan();
		
		
		$this->value_assign($array);
		
		echo json_encode($data); 
	}
	
	public function reject_keuangan()
	{
		$data		= $this->Sppd_model->approval_keuangan(null,'Not Approve Keuangan');
		echo json_encode($data);
	}
	
	public function approval_dirkeuangan(){
		$perdin	= $this->Sppd_model->get_pengajuperdin($this->input->post('idPerdin'));
		
//		$email	= $this->Model_email->get_data(null,null,array('akses_menu' => 'Pencairan'));
		
		$data		= $this->Sppd_model->approval_dirkeuangan();
		
		
		$this->value_assign($array);
		echo json_encode($data); 
	}
	
	public function approval_pencairan(){ 
		$perdin	= $this->Sppd_model->get_pengajuperdin($this->input->post('idPerdin'));
		
	//	$email	= $this->Model_email->get_data(null,null,array('akses_menu' => 'Cash Advance'));
				
		$data		= $this->Sppd_model->approval_pencairan();
		
		
		$this->value_assign($array);
		
		redirect(base_url()."sppd/approve_pencairan");

		// echo json_encode($data); 
	}
	
	public function reject_pencairan()
	{
		$data		= $this->Sppd_model->approval_pencairan(null,'Not Approve Pencairan');
		echo json_encode($data);
	}
	
	public function approval_ca(){ 
		$anggaran	=	$this->Model_keuangan->get_currentanggaranperdin();
		//print_r($this->input->post('total'));
		$data = array(
			'saldo' => ($anggaran[0]['nominal']-$this->input->post('total'))
		);
		
						$this->Model_keuangan->operasi_saldo($anggaran[0]['id'],$data);
		$data		= $this->Sppd_model->approval_ca();
		echo json_encode($data); 
	}
	
	public function reject_ca()
	{
		$data		= $this->Sppd_model->approval_ca(null,"Not Approve CA");
		echo json_encode($data); 	
	}
	
	public function ajx_update_makansaku(){
		$data	= $this->Sppd_model->ajx_update_makansaku();
		echo json_encode($data); 
	}
	
	public function ajx_update_transport(){
		$data	= $this->Sppd_model->ajx_update_transport();
		echo json_encode($data); 
	}
	
	public function ajx_update_penginapan(){
		$data	= $this->Sppd_model->ajx_update_penginapan();
		echo json_encode($data); 
	}
	
	public function ajx_update_dalamkota(){
		$data	= $this->Sppd_model->ajx_update_dalamkota();
		echo json_encode($data); 
	}

	public function ajx_update_jmlhari() {
		$data   = $this->Sppd_model->ajx_update_jmlhari();
		echo json_encode($data);
	}

	public function ajx_update_harimenginap() {
		$data   = $this->Sppd_model->ajx_update_harimenginap();
		echo json_encode($data);
	}
	
	public function Dibaca($value) {
                $angkaBaca = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
                switch ($value) {
                    case ($value < 12):
                        echo " " . $angkaBaca[$value];
                        break;
                    case ($value < 20):
                        echo $result = $this->Dibaca($value - 10) . " belas";
                        break;
                    case ($value < 100):
                        echo $this->Dibaca($value / 10);
                        echo " puluh ";
                        echo $this->Dibaca($value % 10);
                        break;
                    case ($value < 200):
                        echo " seratus ";
                        echo $this->Dibaca($value - 100);
                        break;
                    case ($value < 1000):
                        echo $this->Dibaca($value / 100);
                        echo " ratus";
                        echo $this->Dibaca($value % 100);
                        break;
                    case ($value < 2000):
                        echo " seribu ";
                        echo $this->Dibaca($value - 1000);
                        break;
                    case ($value < 1000000):
                        echo $this->Dibaca($value / 1000);
                        echo " ribu ";
                        echo $this->Dibaca($value % 1000);
                        break;
                    case ($value < 1000000000):
                        echo $this->Dibaca($value / 1000000);
                        echo " juta ";
                        echo $this->Dibaca($value % 1000000);
                        break;
                }
            }
	
	public function convert_number_to_words(){
		$nominal = $this->input->post('nominal');//4575000 
		$data = $this->Sppd_model->convert_number_to_words($nominal);
		echo $data;//ucwords($this->Dibaca($nominal));
	}
	
	/*
	public function get_notif()
	{	
		$output = array_filter($this->session->userdata('menu'),function($object){
			$menu_notif = array(
				'Persetujuan Atasan',
				'Persetujuan HCM',
				'Persetujuan Direktur Keuangan',
				'Persetujuan Keuangan',
				'Persetujuan Personalia',
				'Pencairan',
				'Cash Advance',
				'Laporan Monitoring'
				);
		  return (in_array($object,$menu_notif));
		});
		
		$data = array();
		
		foreach($output as $val)
		{
			
			$data[$val] =(method_exists($this->Sppd_model,'getnotif_'.str_replace(' ','',$val)))?$this->Sppd_model->{'getnotif_'.str_replace(' ','',$val)}():'';
		}
		
		echo json_encode($data);
		
	}
	*/
	public function get_notif($option = null) {
		$user_id = $this->input->post("user_id");

		if($user_id) {
					// return query merupakan daftar menu yang mendapatkan akses admin
			$listAdminMenu = $this->User_menu_model->select("akses_menu")
									->join("tree_menu", "tree_menu.id = user_menu.id_treemenu")
									->where(array("user_menu.id_user" => $user_id, "user_menu.akses_menu_status != " => ''))
									->find_all();

			$data = array(); // fill by list of akses menu array
			$return = array(); // fill by list of notification

			foreach($listAdminMenu as $value) {
				$data[] = $value->akses_menu;
			}

			if(in_array("Persetujuan Atasan", $data)) {
				// cek daftar bawahan
				$listChild = $this->Users_model->select("user_name")
											->where("sppd_atasan", $user_id)
											->find_all();

				$child = array(); // fill by list of child

				foreach($listChild as $value) {
					$child[] = $value->user_name;
				}

				// cek daftar perdin dengan user_insert bawahan & status pengajuan

				$return["SPPD"]["Persetujuan Atasan"] = $this->Perdin_model->select("perdin.id, user_insert, tujuan_kota, keperluan, tgl_berangkat, tgl_kembali, GROUP_CONCAT(perdin_peserta.nama) AS peserta, (SUM(uangmakan_saku)*jml_hari) AS ums, SUM(uangtransportasi) AS ut, (SUM(uangpenginapan)*menginap) AS up, SUM(uangdalamkota) AS udk")
																		->join("perdin_peserta", "perdin_peserta.no_sppd = perdin.id")
																		->where("status", "Pengajuan")
																		->where_in("user_insert", $child)
																		->group_by("perdin_peserta.no_sppd")
																		->find_all();
			}
			if(in_array("Persetujuan Admin Personalia", $data)) {
				$return["SPPD"]["Persetujuan Admin Personalia"] = $this->Perdin_model->select("id, user_insert, tujuan_kota, keperluan, tgl_berangkat, tgl_kembali, GROUP_CONCAT(perdin_peserta.nama) AS peserta, (SUM(uangmakan_saku)*jml_hari) AS ums, SUM(uangtransportasi) AS ut, (SUM(uangpenginapan)*menginap) AS up, SUM(uangdalamkota) AS udk")
												->join("perdin_peserta", "perdin_peserta.no_sppd = perdin.id")
												->where("status", "Approval Atasan")
												->group_by("perdin_peserta.no_sppd")
												->find_all();
			}
			if(in_array("Persetujuan HCM", $data)) {
				$return["SPPD"]["Persetujuan HCM"] = $this->Perdin_model->select("id, user_insert, tujuan_kota, keperluan, tgl_berangkat, tgl_kembali, GROUP_CONCAT(perdin_peserta.nama) AS peserta, (SUM(uangmakan_saku)*jml_hari) AS ums, SUM(uangtransportasi) AS ut, (SUM(uangpenginapan)*menginap) AS up, SUM(uangdalamkota) AS udk")
												->join("perdin_peserta", "perdin_peserta.no_sppd = perdin.id")
												->where("status", "Approval Admin Personalia")
												->find_all();
			}
			if(in_array("Persetujuan Budget Control", $data)) {
				$return["SPPD"]["Persetujuan Budget Control"] = $this->Perdin_model->select("id, user_insert, tujuan_kota, keperluan, tgl_berangkat, tgl_kembali, GROUP_CONCAT(perdin_peserta.nama) AS peserta, (SUM(uangmakan_saku)*jml_hari) AS ums, SUM(uangtransportasi) AS ut, (SUM(uangpenginapan)*menginap) AS up, SUM(uangdalamkota) AS udk")
												->where("status", "Approval HCM")
												->find_all();
			}
			if(in_array("Persetujuan Akunting", $data)) {
				$return["SPPD"]["Persetujuan Akunting"] = $this->Perdin_model->select("id, user_insert, tujuan_kota, keperluan, tgl_berangkat, tgl_kembali, GROUP_CONCAT(perdin_peserta.nama) AS peserta, (SUM(uangmakan_saku)*jml_hari) AS ums, SUM(uangtransportasi) AS ut, (SUM(uangpenginapan)*menginap) AS up, SUM(uangdalamkota) AS udk")
												->where("status", "Approval Budget Control")
												->find_all();
			}
			if(in_array("Persetujuan Keuangan", $data)) {
				$return["SPPD"]["Persetujuan Keuangan"] = $this->Perdin_model->select("id, user_insert, tujuan_kota, keperluan, tgl_berangkat, tgl_kembali, GROUP_CONCAT(perdin_peserta.nama) AS peserta, (SUM(uangmakan_saku)*jml_hari) AS ums, SUM(uangtransportasi) AS ut, (SUM(uangpenginapan)*menginap) AS up, SUM(uangdalamkota) AS udk")
												->where("status", "Approval Akunting")
												->find_all();
			}
			if(in_array("Persetujuan Direktur Keuangan", $data)) {
				$return["SPPD"]["Persetujuan Direktur Keuangan"] = $this->Perdin_model->select("id, user_insert, tujuan_kota, keperluan, tgl_berangkat, tgl_kembali, GROUP_CONCAT(perdin_peserta.nama) AS peserta, (SUM(uangmakan_saku)*jml_hari) AS ums, SUM(uangtransportasi) AS ut, (SUM(uangpenginapan)*menginap) AS up, SUM(uangdalamkota) AS udk")
												->where("status", "Approval Akunting")
												->find_all();
			}
			if(in_array("Persetujuan CA", $data)) {
				$return["SPPD"]["Persetujuan CA"] = $this->Perdin_model->select("id, user_insert, tujuan_kota, keperluan, tgl_berangkat, tgl_kembali, GROUP_CONCAT(perdin_peserta.nama) AS peserta, (SUM(uangmakan_saku)*jml_hari) AS ums, SUM(uangtransportasi) AS ut, (SUM(uangpenginapan)*menginap) AS up, SUM(uangdalamkota) AS udk")
												->where("status", "Realisasi")
												->find_all();
			}

			if($option == "disablePrint") {

			}
			else {
				echo json_encode($return);
			}

			return $return;

		}
	}

	public function get_web_notif() {

		$data = $this->get_notif("disablePrint");

		$htmlTag = '<li class="nav-header">
						<i class="icon-warning-sign"></i>
						Notifications
					</li>';

		$counter = array();

		for($i=0;$i<=count($data["SPPD"]);$i++) {
			$tipe = key($data["SPPD"]);
			if(!isset($counter[$tipe])) {
				$counter[$tipe] = 1;
			}
			else {
				$counter[$tipe]++;
			}
		}

		for($i=0;$i<=count($counter);$i++) {

		$htmlTag .= '<li id="notif_text">
						<a href="#">
							<div class="clearfix">
								<span class="pull-left">
									<i class="btn btn-mini no-hover btn-info icon-fighter-jet"></i>
									<span>'.key($counter).'</span>
								</span>
								<span class="pull-right badge badge-info">+'.$counter[key($counter)].'</span>
							</div>
						</a>
					</li>';

		}

		echo $htmlTag;

	}
	
	public function get_month_report($month, $year) {
		// $approval = $this->input->post('approval');
		// $data = $this->Perdin_model->select('keperluan, pengaju, tgl_pengajuan, tgl_berangkat, tgl_kembali, nominal')
		// $month = $this->input->post('month');

		$date = array(
			"MONTH(perdin.tgl_insert)"	=> $month,
			"YEAR(perdin.tgl_insert)"	=> $year
			);

		$status = array(
			"Approval Admin Personalia",
			"Approval HCM",
			"Approval Budget Control",
			"Approval Akunting",
			"Approval Keuangan",
			"Approval Direktur Keuangan",
			"Approval Direktur Utama",
			"Approval Pencairan",
			"Realisasi",
			"Approval CA"
			);

		$data['month'] = $month;
		$data['year'] = $year;
		$data['data'] = $this->Perdin_model->select('keperluan, user_insert, perdin.tgl_insert, tgl_berangkat, tgl_kembali, GROUP_CONCAT(perdin_peserta.nama separator ",") AS peserta, SUM(uangmakan_saku+uangtransportasi+uangpenginapan+uangdalamkota) AS nominal, perdin.tujuan_kota')
		// $data['data'] = $this->Perdin_model->select('keperluan, user_insert, perdin.tgl_insert, tgl_berangkat, tgl_kembali, GROUP_CONCAT(perdin_peserta.nama separator ",") AS peserta, SUM(uangmakan_saku+uangtransportasi+uangpenginapan+uangdalamkota) AS nominal, perdin.tujuan_kota, GROUP_CONCAT(fin_detail_pengajuan_dana.id SEPARATOR ",") AS perkiraan')
									->join('perdin_peserta', 'perdin_peserta.no_sppd = perdin.id')
									->join("fin_pengajuan_dana", "fin_pengajuan_dana.perdin_id = perdin.id", "left")
									->join("fin_detail_pengajuan_dana", "fin_detail_pengajuan_dana.pengajuan_id = fin_pengajuan_dana.id", "left")
									->where($date)
									->where_in("perdin.status", $status)
									->group_by('perdin.id')
									// ->group_by('fin_pengajuan_dana.id')
									->order_by('perdin.tgl_insert', 'desc')
									->find_all();

		$this->load->view('page/sppd/export_spreadsheet', $data);
		// echo json_encode($data);
	}

	public function unapprove($id) {
		if(isset($id)) {
			$data = array(
				'status'	=> 'Pengajuan'
				);

			if($this->Perdin_model->update($id, $data)) {
				redirect(base_url().'sppd/approve_sdm');
			}
		}
		else {
			show_404();
		}
	}

	// added from keuangan controller	
	public function ajx_currentanggaranperdin()
	{
		$data = $this->Model_keuangan->get_currentanggaranperdin();
		$sppd = $this->Sppd_model->view_perdin_modal();
		$total= 0;
		//u_transportasi+(u_penginapan*data.dataPerdin.jml_hari)+u_dalam_kota+(u_uangmakan_saku*data.dataPerdin.jml_hari)
		//print_r($sppd);
		foreach($sppd['dataPeserta'] as $row){
			
			$total	=	 $total + ($row['uangtransportasi']+($row['uangpenginapan']*$sppd['dataPerdin']['jml_hari'])+$row['uangdalamkota']+($row['uangmakan_saku']*$sppd['dataPerdin']['jml_hari']));
			//print_r($row);
		}
		$data[0]['total']	= $total;
		echo json_encode(array($data[0]));
	}
	// end add

	private function dataAtasanSppd($sppd_atasan_id) { // to get atasan based on sppd_atasan
		$data = $this->Users_model->select("nama, posisi")
									->find($sppd_atasan_id);

		return $data;
	}

	private function postCURL($url, $param) {

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		// curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($param));
		curl_setopt($ch, CURLOPT_POSTFIELDS, $param);

		$result = curl_exec($ch);

		curl_close($ch);

	}

	public function testApi($idPerdin) {
		$data["perdin"] = $this->dataPerdin($idPerdin);
		$data["peserta"] = $this->dataPeserta($idPerdin);

		$this->postCURL("http://localhost/sdm_ci/api/addDuty", json_encode($data));
	}

	public function getDataPerdin($idPerdin) {
		$data = $this->dataPerdin($idPerdin);

		echo json_encode($data);	
	}
	public function getPesertaPerdin($idPerdin) {
		$data = $this->dataPeserta($idPerdin);

		echo json_encode($data);
	}

	private function dataPerdin($idPerdin) { // to load summary of pengajuan dinas
		$data = $this->Perdin_model->select("perdin.id, perdin.tgl_insert, perdin.status, perdin.tujuan, perdin.tujuan_kota, perdin.tgl_berangkat, perdin.tgl_kembali, perdin.user_insert, perdin.jml_hari, perdin.menginap, perdin.keperluan, SUM(uangmakan_saku)*jml_hari AS ums, SUM(uangtransportasi) AS ut, SUM(uangpenginapan)*menginap AS up, SUM(uangdalamkota) AS udk, transport, penginapan, approval_atasan, GROUP_CONCAT(perdin_peserta.nama SEPARATOR ',') AS peserta, users.posisi, struktur.unit_kerja, struktur.kode_struktur")
									->join("perdin_peserta", "perdin_peserta.no_sppd = perdin.id")
									->join("users", "users.user_name = perdin.user_insert", "left")
									->join("struktur", "struktur.id = users.id_unitkerja", "left")
									->group_by("perdin_peserta.no_sppd")
									->find($idPerdin);

		return $data;
	}

	private function dataPeserta($idPerdin) { // to load peserta and budget each person
		$data = $this->Perdin_peserta_model->select("perdin_peserta.nama, perdin_peserta.uangmakan_saku AS ums, perdin_peserta.uangtransportasi AS ut, perdin_peserta.uangpenginapan AS up, perdin_peserta.uangdalamkota AS udk, users.posisi, users.nik")
											->join("users", "users.user_name = perdin_peserta.nama", "left")
											->where("no_sppd", $idPerdin)
											->find_all();

		return $data;
	}

	private function dataRincian($idPerdin) { // to load agenda
		$data = $this->Perdin_rincian_model->select("tgl, waktu, agenda, lokasi")
											->where("no_sppd", $idPerdin)
											->find_all();

		return $data;
	}

	// ajax end
	public function popup_sppd() {
		$idPerdin = $this->input->post("idPerdin");

		$data["dataPerdin"] = $this->dataPerdin($idPerdin);

		$data["dataPeserta"] = $this->dataPeserta($idPerdin);

		$data["dataRincian"] = $this->dataRincian($idPerdin);

		$data["total"] = ($data["dataPerdin"]->ums + $data["dataPerdin"]->ut + $data["dataPerdin"]->up + $data["dataPerdin"]->udk);

		$data["terbilang"] = $this->terbilang($data["total"]);

		$this->load->view("page/sppd/modal_sppd", $data);
	}

	public function popup_formulir() {
		$idPerdin = $this->input->post('idPerdin');

		$data["dataPerdin"] = $this->dataPerdin($idPerdin);

		$data["total"] = ($data["dataPerdin"]->ums + $data["dataPerdin"]->ut + $data["dataPerdin"]->up + $data["dataPerdin"]->udk);

		$data["terbilang"] = $this->terbilang($data["total"]);

		$this->load->view("page/sppd/modal_formulir", $data);
	}

	public function get_nomor($idPerdin) {
		echo $this->generateNomorSppd($idPerdin);
	}

	public function testInputSurat($idPerdin) {
		$this->insertSurat($idPerdin);
	}

	private function insertSurat($idPerdin) {

		// prepare data untuk di input ke surat
		$dataPerdin = $this->dataPerdin($idPerdin);

		//echo json_encode($dataPerdin);

		$nomorSurat = $this->generateNomorSppd($idPerdin);

		$data = array(
			"tipe_surat"	=> "internal",
			"jenis_surat"	=> "SPPD",
			"nomor_surat"	=> $nomorSurat,
			"nama_surat"	=> $dataPerdin->keperluan, // to replace later
			"tujuan"		=> $dataPerdin->peserta, // to replace later
			"dari"			=> $dataPerdin->kode_struktur, // to replace later
			"tgl_surat"		=> date("d-m-Y"), // to replace later
			"bulan"			=> date("m"), // to replace later
			"tahun"			=> date("Y") // to replace later
			);

		//echo "<br/><br/>";
		//echo json_encode($data);

		if($this->Surat_model->insert($data)) {
			
			$data = array(
				"nomor_surat"	=> $nomorSurat
				);

			$this->Perdin_model->update($idPerdin, $data);
		}

		return true;
	}

	private function generateNomorSppd($idPerdin) {
		
		$dataPerdin = $this->dataPerdin($idPerdin);

		$dataSurat = array(
			"jenis_surat" 	=> "SPPD",
			"bulan" 		=> date("m", strtotime($dataPerdin->tgl_insert)),
			"tahun"			=> date("Y", strtotime($dataPerdin->tgl_insert))
			);

		$year = date("Y", strtotime($dataPerdin->tgl_insert)); // tahun tgl_insert
		$monthRomawi = $this->getMonthRomawi(date("n", strtotime($dataPerdin->tgl_insert))); // bulan tgl_insert
		$company = "BIJB";
		$dep = $dataPerdin->kode_struktur; // to dynamic based on user_insert sppd
		$type = "SPPD";
		$number = $this->Surat_model->select("COUNT(id) AS total")
										->find_by($dataSurat);

		$current_number = $number->total+1;

		return $current_number."/".$type."-".$dep."/".$company."/".$monthRomawi."/".$year;
	}

	private function getMonthRomawi($month) {
		$romawi = array(
			1 	=> "I",
			2 	=> "II",
			3 	=> "III",
			4 	=> "IV",
			5 	=> "V",
			6 	=> "VI",
			7 	=> "VII",
			8 	=> "VIII",
			9 	=> "IX",
			10 	=> "X",
			11 	=> "XI",
			12	=> "XII"
			);

		return $romawi[$month];
	}
}
