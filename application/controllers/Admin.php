<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends MY_Controller {

	private $module = "admin";

	public function __construct() {
		parent::__construct();

		$this->load->library("Authentication");
		Authentication::is_logged();

		$this->load->library("Property");
		$this->load->library("Template");

		$this->load->model("Site_model");
		$this->load->model("Menu_model");
		$this->load->model("Users_model");
		$this->load->model("Groups_model");
		$this->load->model("User_group_model");
		$this->load->model("Permissions_model");

		Template::set_module($this->module);
		Template::set_menu();
		Template::set_header("dashboard/header");
		Template::set_footer("dashboard/footer");
	}

	public function index() {
		Template::set_title("Admin", "Admin Configuration");
		Template::set_body("admin/index");
		Template::render();
	}

	public function site() {
		$data["data"] = $this->Site_model->order_by("name", "asc")
											->find_all();

		Template::set_title("Site", "Site Configuration");
		Template::set_body("admin/site", $data);
		Template::render();
	}

	public function add_site() {
		if($this->input->post("submit")) {
			$data = array(
				"name"	=> $this->input->post("name"),
				"value"	=> $this->input->post("value")
				);

			if($this->Site_model->insert($data)) {
				// with success message
				redirect(base_url()."admin/site");
			}
			else {
				// with error message
				redirect(base_url()."admin/site");
			}
		}

		Template::load_modal("admin/modal_add_site", null);
	}

	public function set_site() {
		if($this->input->post("submit")) {
			$id = $this->input->post("name");

			$data = array(
				"value"	=> $this->input->post("value")
				);

			if($this->Site_model->update($id, $data)) {
				// with success message
				redirect(base_url()."admin/site");
			}
			else {
				// with error message
				redirect(base_url()."admin/site");
			}
		}

		$id = $this->input->post("name");

		$data["site"] = $this->Site_model->find($id);

		Template::load_modal("admin/modal_add_site", $data);
	}

	public function users() {

		$data["data"] = $this->Users_model->find_all();

		Template::set_title("Users", "User Configuration");
		Template::set_body("admin/list_users", $data);
		Template::set_footer_script("admin/list_menu_script");
		Template::render();
	}

	public function add_user() {
		if($this->input->post("submit")) {
			$data = array(
				"user_login"	=> $this->input->post("email"),
				"user_name"		=> $this->input->post("name"),
				"password"		=> md5($this->input->post("password"))
				);

			if($this->Users_model->insert($data)) {
				// with success message
				redirect(base_url()."admin/users");
			}
			else {
				// with error message
				redirect(base_url()."admin/users");
			}
		}

		Template::load_modal("admin/modal_add_user", null);
	}

	public function set_user() {
		if($this->input->post("submit")) {
			$id = $this->input->post("id");

			$data = array(
				"email"		=> $this->input->post("email"),
				"name"		=> $this->input->post("name"),
				"password"	=> md5($this->input->post("password"))
				);

			if($this->Users_model->update($id, $data)) {
				// with success message
				redirect(base_url()."admin/users");
			}
			else {
				// with error message
				redirect(base_url()."admin/users");
			}

		}

		$id = $this->input->post("id");

		$data["user"]	= $this->Users_model->find($id);

		Template::load_modal("admin/modal_add_user", $data);
	}

	public function delete_user() {
		if($this->input->post("submit")) {
			$id = $this->input->post("id");

			if($this->Users_model->delete($id)) {
				// with success message
				redirect(base_url()."admin/users");
			}
			else {
				// with error message
				redirect(base_url()."admin/users");
			}
		}

		$data["id"]	= $this->input->post("id");

		Template::load_modal("admin/delete_confirmation", $data);
	}

	public function menu() {

		$data["data"] = $this->Menu_model->order_by("module", "asc")
											->order_by("weight", "asc")
											->find_all();

		Template::set_title("Menu", "Menu Configuration");
		Template::set_body("admin/list_menu", $data);
		Template::set_footer_script("admin/list_menu_script");
		Template::render();		
	}

	public function add_menu() {
		if($this->input->post("submit")) {
			$data = array(
				"module"	=> $this->input->post("module"),
				"menu"		=> $this->input->post("menu"),
				"link"		=> $this->input->post("link"),
				"weight"	=> $this->input->post("weight"),
				"icon"		=> $this->input->post("icon"),
				"parent_id"	=> $this->input->post("parent_id")
				);

			if($this->Menu_model->insert($data)) {
				// with success message
				redirect(base_url()."admin/menu");
			}
			else {
				// with error message
				redirect(base_url()."admin/menu");
			}
		}

		$condition = array(
			"parent_id"	=> 0
			);
		
		$data["parent_menu"] = $this->Menu_model->select("id, module, menu, parent_id")
												->where($condition)
												->order_by("module", "asc")
												->find_all();

		Template::load_modal("admin/modal_add_menu", $data);
	}

	public function set_menu() {
		if($this->input->post("submit")) {
			$id = $this->input->post("id");
			
			$data = array(
				"module"	=> $this->input->post("module"),
				"menu"		=> $this->input->post("menu"),
				"link"		=> $this->input->post("link"),
				"weight"	=> $this->input->post("weight"),
				"icon"		=> $this->input->post("icon"),
				"parent_id"	=> $this->input->post("parent_id")
				);

			if($this->Menu_model->update($id, $data)) {
				// with success message
				redirect(base_url()."admin/menu");
			}
			else {
				// with error message
				redirect(base_url()."admin/menu");
			}

		}

		$id = $this->input->post("id");

		$data["menu"]	= $this->Menu_model->find($id);

		$condition = array(
			"id !="	=> $id,
			"parent_id"	=> 0
			);
		
		$data["parent_menu"] = $this->Menu_model->select("id, module, menu, parent_id")
												->where($condition)
												->order_by("module", "asc")
												->find_all();

		Template::load_modal("admin/modal_add_menu", $data);
	}

	public function delete_menu() {
		if($this->input->post("submit")) {
			$id = $this->input->post("id");

			if($this->Menu_model->delete($id)) {
				// with success messagee
				redirect(base_url()."admin/menu");
			}
			else {
				// with error message
				redirect(base_url()."admin/menu");
			}
		}

		$data["id"]	= $this->input->post("id");

		Template::load_modal("admin/delete_confirmation", $data);
	}

	public function groups() {

		$data["data"] = $this->Groups_model->find_all();

		Template::set_title("Groups", "Groups");
		Template::set_body("admin/groups", $data);
		Template::render();
	}

	public function add_group() {
		if($this->input->post("submit")) {
			$data = array(
				"name"			=> $this->input->post("name"),
				"description"	=> $this->input->post("description")
			);

			if($this->Groups_model->insert($data)) {
				Template::set_message("Group has been added");
				redirect(base_url()."admin/groups");
			}
			else {
				Template::set_message("Group can't be added");
				redirect(base_url()."admin/groups");
			}
		}
		Template::load_modal("admin/modal_add_group");
	}

	public function set_group() {
		if($this->input->post("submit")) {
			$id = $this->input->post("id");

			$data = array(
				"name"			=> $this->input->post("name"),
				"description"	=> $this->input->post("description")
			);

			if($this->Groups_model->update($id, $data)) {
				Template::set_message("Group has been updated");
				redirect(base_url()."admin/groups");
			}
			else {
				Template::set_message("Group can't be updated");
				redirect(base_url()."admin/groups");
			}
		}
		$data["id"] = $this->input->post("id");
		$data["group"] = $this->Groups_model->find($data["id"]);

		Template::load_modal("admin/modal_add_group", $data);
	}

	public function delete_group() {
		if($this->input->post("submit")) {
			$id = $this->input->post("id");

			if($this->Groups_model->delete($id)) {
				Template::set_message("Group has been deleted");
				redirect(base_url()."admin/groups");
			}
			else {
				Template::set_message("Group can't be deleted");
				redirect(base_url()."admin/groups");
			}
		}
		$data["id"] = $this->input->post("id");

		Template::load_modal("admin/delete_confirmation", $data);
	}

	public function set_user_group() {
		if($this->input->post("submit")) {

			// $group = $this->input->post("group_id");
			$group = $this->input->post("group_id");
			$users = $this->input->post("users");

			// remove all current_group if group_id exist
			// NEED FIX TO UPDATE CHANGE ONLY
			if($this->User_group_model->where("group_id", $group)->find_all()) {

				$cond = array(
					"group_id"	=> $group
				);

				$this->User_group_model->delete_where($cond);
			}

			foreach($users as $key) {
				$data = array(
					"group_id"	=> $group,
					"user_id"	=> $key
				);

				if(!$this->User_group_model->find_by($data)) {
					$this->User_group_model->insert($data);
				}
			}

			Template::set_message("success", "User group has been updated");
			redirect(base_url()."admin/groups");
		}

		$data["group_id"] = $this->input->post("id");

		$data["users"] = $this->Users_model->select("user_id, user_name")
											->order_by("user_name", "asc")
											->find_all();

		$checked_user = $this->User_group_model->where("group_id", $data["group_id"])
												->find_all();

		$data["checked"] = $this->get_value_of_array($checked_user, "user_id");

		Template::load_modal("admin/modal_set_user_group", $data);
	}

	public function permissions() {
		$data["data"] = $this->Menu_model->select("*")
											->order_by("menu_new.module", "asc")
											->order_by("menu_new.weight", "asc")
											->find_all();

		Template::set_title("Permissions", "Permissions Configuration");
		Template::set_body("admin/list_permissions", $data);
		Template::render();		
	}

	public function set_permission() {
		// $data["menu"] = $this->Menu_model->find_all();
		if($this->input->post("submit")) {
			// $data = array();
			$menu_id = $this->input->post("menu_id");
			$group_data = $this->input->post("group");
			$admin = $this->input->post("admin");

			if($this->Permissions_model->where("menu_id", $menu_id)->find_all()) {
				$cond = array(
					"menu_id"	=> $menu_id
				);

				$this->Permissions_model->delete_where($cond);
			}

			$menu = $this->Menu_model->find($menu_id);

			foreach($group_data as $value) {

				$group = $this->Groups_model->find($value);

				$data = array(
					"name"		=> ucwords(str_replace(" ", "", $menu->module)).".".ucwords(str_replace(" ","", $menu->menu)),
					"menu_id"	=> $menu_id,
					"group_id"	=> $value,
					"is_admin"	=> (in_array($value, $admin)) ? 1 : 0
				);

				$this->Permissions_model->insert($data);
			}

			// Template::set_message("success", json_encode($test));
			Template::set_message("success", "Permission has been updated");
			redirect(base_url()."admin/permissions");
		}

		$data["menu_id"] = $this->input->post("id");
		$data["groups"] = $this->Groups_model->find_all();
		$data["permission"] = $this->Permissions_model->select("group_id, is_admin")
														->where("menu_id", $this->input->post("id"))
														->find_all();

		$index = array(
			"group_id",
			"is_admin"
		);

		$data["access"] = $this->get_value_of_array($data["permission"], $index);
		
		Template::load_modal("admin/modal_set_permission", $data);
	}

	public function profile() {
		Template::set_body("admin/profile");
		Template::render();
	}

	public function session() {
		// echo json_encode($this->session->userdata("logged_in"));
		echo json_encode($this->session->userdata("logged_in"));
	}

	private function get_value_of_array($array, $field) { // to convert array with field to list value

		$data = array();

		if(is_array($array) && !empty($array)) {
			if(is_array($field) && !empty($field)) {
				foreach($array as $value) {

					foreach($field as $subvalue) {
						$subdata = array();

						$temp = array(
							$subvalue => $value->$subvalue
						);

						echo json_encode($temp);

						array_push($subdata, $temp);
					}

					$data[$value->group_id] = $subdata;
				}
			}
			else {
				foreach($array as $value) {
					array_push($data, $value->$field);
				}
			}
		}

		return $data;
	}
}
?>