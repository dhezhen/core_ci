<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * CodeIgniter Authentication Class
 *
 * Simple authentication stuff for a simple web apps
 *
 * @package		CodeIgniter
 * @subpackage	Authentication
 * @category	Authentication
 * @author		Raydeon
 * @link		http://www.drivino.com
 */
class Authentication {

	public function __construct() {
		// $CI =& get_instance();

		// $CI->load->model("Users_model");
	}

	public static function user_check($email, $password) {
		$ci =& get_instance();
		$ci->load->model("Users_model");

		$condition = array(
			"user_login" 	=> $email, // edited due mismatch login mechanism, email to user_login
			"password"		=> $password
			);

		$user_data = $ci->Users_model->select("user_id as id, user_name as name, user_login as email, nik, user_avatar AS avatar")
												->find_by($condition);

		if($user_data) {

			$avatar_location = "assets/upload/avatar/".$user_data->avatar;
			$default_avatar = "assets/upload/avatar/default.jpg";
			$user_data->avatar = file_exists($avatar_location) ? base_url().$avatar_location : base_url().$default_avatar ;
			// $user_data->avatar = base_url()."/assets/upload/avatar/default.jpg";

			$ci->session->set_userdata("logged_in", $user_data);

			return true;
		}
		else {

			return false;
		}
	}

	public static function sso_check($email) {
		$ci =& get_instance();
		$ci->load->model("Users_model");

		$condition = array(
			"user_login" 	=> $email, // edited due mismatch login mechanism, email to user_login
			);

		$user_data = $ci->Users_model->select("user_id as id, user_name as name, user_login as email, nik, user_avatar AS avatar")
												->find_by($condition);

		if($user_data) {

			$avatar_location = "assets/upload/avatar/".$user_data->avatar;
			$default_avatar = "assets/upload/avatar/default.jpg";
			$user_data->avatar = file_exists($avatar_location) ? base_url().$avatar_location : base_url().$default_avatar ;
			// $user_data->avatar = base_url()."/assets/upload/avatar/default.jpg";

			$ci->session->set_userdata("logged_in", $user_data);

			return true;
		}
		else {

			return false;
		}
	}

	public static function is_logged() {
		$ci =& get_instance();

		if(!$ci->session->userdata("logged_in")) {
			$ci->session->sess_destroy();
			$ci->load->library("Template");

			redirect(base_url());
		}
	}

	public static function is_super() {
		$ci =& get_instance();

		self::is_logged();

		$user = $ci->session->userdata("logged_in");
	}

	public static function has_permission($permission) {
		$ci =& get_instance();

		$ci->load->model("Users_model");

		$userdata = self::get_session();

		$cond = array(
			"users.user_id"		=> $userdata->id,
			// "menu.id"			=> $menu_id,
			"permissions.name"	=> $permission
		);

		/* ver 1
		SELECT users.user_id, users.user_name, groups.name, permissions.menu_id, menu.menu
		FROM users
		JOIN user_group ON user_group.user_id = users.user_id
		JOIN groups ON groups.id = user_group.group_id
		JOIN permissions ON permissions.group_id = groups.id
		JOIN menu ON menu.id = permissions.menu_id
		WHERE users.user_id = 32 AND menu.id = 11
		*/

		/* ver 2
		SELECT users.user_id, users.user_name, groups.name, permissions.menu_id, menu.menu
		FROM users
		JOIN user_group ON user_group.user_id = users.user_id
		JOIN groups ON groups.id = user_group.group_id
		JOIN permissions ON permissions.group_id = groups.id
		JOIN menu ON menu.id = permissions.menu_id
		WHERE users.user_id = 32 AND permissions.name = 'Sdm.PresenceGlobal'
		*/

		$result = $ci->Users_model->select("users.user_id")
									->join("user_group", "user_group.user_id = users.user_id")
									->join("groups", "groups.id = user_group.group_id")
									->join("permissions", "permissions.group_id = groups.id")
									->join("menu_new", "menu_new.id = permissions.menu_id")
									->where($cond)
									->find_all();

		if($result) {
			return true;
		}
		else {
			Template::set_message("error", "You don't have permissions to access page");
			redirect(base_url().Property::$controller);
		}
	}

	public static function is_admin($permission) {
		$ci =& get_instance();

		$ci->load->model("Users_model");

		$userdata = self::get_session();

		$cond = array(
			"users.user_id"			=> $userdata->id,
			// "menu.id"				=> $menu_id,
			"permissions.is_admin"	=> 1,
			"permissions.name"		=> $permission
		);

		/*
		SELECT users.user_id, users.user_name, groups.name, permissions.menu_id, menu.menu
		FROM users
		JOIN user_group ON user_group.user_id = users.user_id
		JOIN groups ON groups.id = user_group.group_id
		JOIN permissions ON permissions.group_id = groups.id
		JOIN menu ON menu.id = permissions.menu_id
		WHERE users.user_id = 32 AND menu.id = 11
		*/

		$result = $ci->Users_model->select("users.user_id")
									->join("user_group", "user_group.user_id = users.user_id")
									->join("groups", "groups.id = user_group.group_id")
									->join("permissions", "permissions.group_id = groups.id")
									->join("menu_new", "menu_new.id = permissions.menu_id")
									->where($cond)
									->find_all();

		if($result) {
			return true;
		}
		else {
			return false;
		}		
	}

	private static function get_session() {
		$ci =& get_instance();

		return $ci->session->userdata("logged_in");
	}
}
?>