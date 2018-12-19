<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function __construct() {
		parent::__construct();

		$this->load->library("Authentication");
		$this->load->library("Property");
		$this->load->library("Template");

		$this->load->model("Users_model");

		Template::set_header("home/header");
		Template::set_footer("home/footer");
	}

	public function index()
	{

		if($this->session->userdata("logged_in")) {
			redirect("dashboard");
		}
		
		Template::set_body("home/index");
		Template::render();
	}

	public function login() { // must limit try by ip address
		$email = $this->input->post("email");
		$isSso = $this->input->post("sso");

		if($isSso) {
			$validation = Authentication::sso_check($email);

			if($validation) {
				redirect(base_url()."document");
			}
			else {
				redirect(base_url()."home/login");
			}
		}
		else {
			$validation = Authentication::user_check($this->input->post("email"), md5($this->input->post("password")));

			if($validation) {
				redirect("dashboard"); // default landing apps
			}
			else {
				Template::set_message("error", "Email or Password are invalid");
				redirect();
			}
		}
	}

	public function logout() {
		$this->session->sess_destroy();
		redirect(base_url());
	}

	public function md5_generator($value) {
		echo md5($value);
	}
}
