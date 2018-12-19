<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {
	
	public $session_data;

	public function __construct() {
		parent::__construct();

		$this->session_data = $this->session->userdata("logged_in");
	}
}
?>