<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->helper(array('form', 'url'));
		$this->load->library("Authentication");
		$this->load->library("Property");
		$this->load->library("Template");
		$this->load->library('upload');
		Authentication::is_logged();
		
		$this->load->model("Partners_model");
		$this->load->model("Products_model");
		$this->load->model("Advertise_model");
		
		Template::set_module("dashboard");
		Template::set_menu();
		Template::set_header("dashboard/header");
		Template::set_footer("dashboard/footer");
	}

	public function index() {
		Template::set_title("Menu Manager","Dashboard");
		Template::set_body("dashboard/index");
		Template::render();
	}	
		
	public function manage_advertise(){
		$data["data"] = $this->Advertise_model->find_all();
		Template::set_title("Manage Advertisement","Advertise Configuration");
		Template::set_body("dashboard/manage_advertise",$data);
		Template::render();
	}
	
	public function add_advertiser(){
		if ($this->input->post("submit")){
			$data = array (
			"bmad_name" 		=>	$this->input->post("bmad_name"),
			"bmad_date_start"	=> 	$this->input->post("bmad_date_start"),
			"bmad_date_end"		=>	$this->input->post("bmad_date_end"),
			"bmad_link"			=>	$this->input->post("bmad_link"),
			"bmad_image"		=>	$this->do_upload("bmad_image"),
			"bmad_status"		=>	$this->input->post("bmad_status")
			);
			if ($this->Advertise_model->insert($data)){
				
				Template::set_message("success","Advertiser has been added");
				redirect(base_url()."dashboard/manage_advertise");
				} else {
				Template::set_message("Error",json_encode($this->db->error()));
				redirect(base_url()."dashboard/manage_advertise");
				}
			}
			Template::load_modal("dashboard/modal_add_advertiser",null);		
		}
		
	
	//edit/update items
	public function set_advertiser() {
		if($this->input->post("submit")) {
			$id = $this->input->post("bmad_id");
			// redirect ("http://google.co.id?id=$id");
			$data = array(
				"bmad_name"			=> 	$this->input->post("bmad_name"),
				"bmad_date_start" 	=> 	$this->input->post("bmad_date_start"),
				"bmad_date_end"		=>	$this->input->post("bmad_date_end"),
				"bmad_image"	=>	$this->do_upload("bmad_image"),
				"bmad_harga"		=>	$this->input->post("bmad_harga"),
				"bmad_link"			=>	$this->input->post("bmad_link"),
				"bmad_status"		=>	$this->input->post("bmad_status")
				);
			if($this->Advertise_model->update($id, $data)) {
				// with success message
				redirect(base_url()."dashboard/manage_advertise");
			}
			else {
				// with error message
				redirect(base_url()."dashboard/manage_advertise");
			}

		}
		$id = $this->input->post("id");

		$data["advertiser"]	= $this->Advertise_model->find($id);

		Template::load_modal("dashboard/modal_add_advertiser", $data);
	}
	
	
	public function delete_advertiser() {
			if($this->input->post("submit")) {
				$id = $this->input->post("id");

				if($this->Advertise_model->delete($id)) {
				// with success message
					redirect(base_url()."dashboard/manage_advertise");
			}
				else {
				// with error message
				redirect(base_url()."dashboard/manage_advertise");
			}
		}

		$data["id"]	= $this->input->post("id");

		Template::load_modal("dashboard/delete_confirmation", $data);
	}
	
	
	public function manage_partner (){
	$data["data"] = $this->Partners_model->find_all();
		Template::set_title("Manage Partner","Partner Configuration");
		Template::set_body("dashboard/manage_partner",$data);
		Template::render();
	}
	
	//adding item 
	public function add_partner () {
		if($this->input->post("submit")) {
		
			$data = array(
				"bmd_nama_vendor"	=> $this->input->post("bmd_nama_vendor"),
				"bmd_kode_vendor"	=> $this->input->post("bmd_kode_vendor"),
				"bmd_jenis"			=> $this->input->post("bmd_jenis"),
				"bmd_url_logo"		=> $this->do_upload("bmd_url_logo"),
				"bmd_vendor_aktif"	=> $this->input->post("bmd_vendor_aktif"),
				"bmd_url_promo"		=> $this->input->post("bmd_url_promo")
							);				
							
			if($this->Partners_model->insert($data)) {
				// with success message
				//Template::set_message("success","Partner has been added");
				redirect(base_url()."dashboard/manage_partner");
				
			}
			else {
				// with error message
				//Template::set_message("Error",json_encode($this->db->error()));
				redirect(base_url()."dashboard/manage_partner");
			}
		}

		Template::load_modal("dashboard/modal_add_partner", null);
	}

	
	//edit/update items
	public function set_partner() {
		if($this->input->post("submit")) {
			$id = $this->input->post("bmd_id_vendor");
			// redirect ("http://google.co.id?id=$id");
			$data = array(
				"bmd_nama_vendor"	=> $this->input->post("bmd_nama_vendor"),
				"bmd_kode_vendor"	=> $this->input->post("bmd_kode_vendor"),
				"bmd_jenis"			=> $this->input->post("bmd_jenis"),
				"bmd_url_logo"		=> $this->do_upload("bmd_url_logo"),
				"bmd_vendor_aktif"	=> $this->input->post("bmd_vendor_aktif"),
				"bmd_url_promo"		=> $this->input->post("bmd_url_promo")
				);

			if($this->Partners_model->update($id, $data)) {
				// with success message
				redirect(base_url()."dashboard/manage_partner");
			}
			else {
				// with error message
				redirect(base_url()."dashboard/manage_partner");
			}

		}
		$id = $this->input->post("id");

		$data["partner"]	= $this->Partners_model->find($id);

		Template::load_modal("dashboard/modal_add_partner", $data);
	}
	
	//delete items
		public function delete_partner() {
		if($this->input->post("submit")) {
			$id = $this->input->post("id");

			if($this->Partners_model->delete($id)) {
				// with success message
				redirect(base_url()."dashboard/manage_partner");
			}
			else {
				// with error message
				redirect(base_url()."dashboard/manage_partner");
			}
		}

		$data["id"]	= $this->input->post("id");

		Template::load_modal("dashboard/delete_confirmation", $data);
	}



	//load page by function
	public  function manage_product(){
		$data["data"] = $this->Products_model->find_all();
			Template::set_title("Manage Produk","Product Configuration");
			Template::set_body("dashboard/manage_product",$data);
			Template::render();
		}
	
	public function add_product () {
		if($this->input->post("submit")) {
			$data = array(
				"bmd_nama_paket"		=> $this->input->post("bmd_nama_paket"),
				"bmd_nama_vendor"		=> $this->input->post("bmd_nama_vendor"),
				"bmd_url_gambarpromo"	=> $this->do_upload("bmd_url_gambarpromo"),
				"bmd_url_paket"			=> $this->input->post("bmd_url_paket"),
				"bmd_harga"				=> $this->input->post("bmd_harga"),
				"bmd_deskripsi"			=> $this->input->post("bmd_deskripsi"),
				"bmd_aktif"				=> $this->input->post("bmd_aktif"),
				"bmd_contact_person"	=> $this->input->post("bmd_contact_person")
				);

			if($this->Products_model->insert($data)) {
				// with success message
				Template::set_message("success","Partner has been added");
				redirect(base_url()."dashboard/manage_product");
				
			}
			else {
				// with error message
				Template::set_message("Error",json_encode($this->db->error()));
				redirect(base_url()."dashboard/manage_product");
			}
		}

		Template::load_modal("dashboard/modal_add_product", null);
	}
		
	
	
	public function set_product(){
		if($this->input->post("submit")){
			$id = $this->input->post("bmd_id");
			//redirect ("http://google.co.id?id=$id");
			$data = array(
				"bmd_nama_paket"		=> $this->input->post("bmd_nama_paket"),
				"bmd_nama_vendor"		=> $this->input->post("bmd_nama_vendor"),
				"bmd_url_gambarpromo"	=> $this->do_upload("bmd_url_gambarpromo"),
				"bmd_url_paket"			=> $this->input->post("bmd_url_paket"),
				"bmd_harga"				=> $this->input->post("bmd_harga"),
				"bmd_deskripsi"			=> $this->input->post("bmd_deskripsi"),
				"bmd_aktif"				=> $this->input->post("bmd_aktif"),
				"bmd_contact_person"	=> $this->input->post("bmd_contact_person")
			);
			
			if($this->Products_model->update($id,$data))
			{
				redirect(base_url()."dashboard/manage_product");
			}
			else
			{	
				redirect(base_url()."dashboard/manage_product");
			}
		}
		$id = $this->input->post("id");
		$data["product"] = $this->Products_model->find($id);
		Template::load_modal("dashboard/modal_add_product",$data);	
			}
			
			
		public function delete_product() {
			if($this->input->post("submit")) {
				$id = $this->input->post("id");

				if($this->Products_model->delete($id)) {
				// with success message
					redirect(base_url()."dashboard/manage_product");
			}
				else {
				// with error message
				redirect(base_url()."dashboard/manage_product");
			}
		}

		$data["id"]	= $this->input->post("id");

		Template::load_modal("dashboard/delete_confirmation", $data);
	}
	
	
	//upload gambar
		public function do_upload($name)
        {
		//redirect("http://google.com");
                $config['upload_path']          = './uploads/photo/';
                $config['file_name']          	= $this->generateRandomString();
                $config['allowed_types']        = 'gif|jpg|png|jpeg';
                $config['max_size']             = 5026;
                //$config['max_width']            = 2048;
                //$config['max_height']           = 1552;

                $this->load->library('upload');
				$this->upload->initialize($config);
				$filename = '';
                if ( ! $this->upload->do_upload($name))
                {
                        //$error = array('error' => $this->upload->display_errors());
                        $error = array('error' => $this->upload->display_errors());
						
						Template::set_message("error", json_encode($error));

                        $this->load->view('dashboard/modal_add_partner', $error);
                }
                else
                {
                        $data = $this->upload->data();

						$filename = $data["file_name"];
						Template::set_message("success",json_encode($data));
                        $this->load->view('dashboard/manage_partner', $data);
                }
				return $filename;
                }
			
			
	private function generateRandomString($length = 10) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}
			
		function displayimage($Id=FALSE){
			if ($Id) 
			{
				$image = $this->MMarches->getImage($Id);
				header("Content-type: image/jpeg");
				print($image);
			}        
			}	
						
			
	
	public function session() {
		echo json_encode($this->session->userdata("logged_in"));
	}
}
?>