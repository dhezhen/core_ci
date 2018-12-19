<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Content extends MY_Controller {
	private $modul =  "content";
	public function __construct () {
			parent ::__construct();
			$this->load_library("Authentication");
			
			Authentication::is_loged();
			
			$this->load->library("Property");
			$this->load->library("Template");
			
			$this->load->model("Menu_manager");
			
			Template::set_module($this->module);
			Template::set_menu();
			Template::set_header("content/header");
			Template::set_header("content/footer");	
			
			}

public function index()	{
			Template::set_title("Content","index");
			Template::set_body(content/index);
			Template::render();

			}		
	
}
?>