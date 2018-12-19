<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * CodeIgniter Template Class
 *
 * Separator part of view
 *
 * @package		CodeIgniter
 * @subpackage	Template
 * @category	Template
 * @author		Raydeon
 * @link		http://www.drivino.com
 */
class Template {
	
	// var $CI;

	public static $module = null;
	public static $header = null;
	public static $body = null;
	public static $footer = null;
	private static $data = array(
		"menu"		=> null,
		"new_menu"	=> null,
		"title"		=> null,
		"subtitle"	=> null,
		"message" 	=> null,
		"header_script"	=> null,
		"footer_script"	=> null
		);
	private static $passing_parameter = null;

	public function __construct() {
		// $this->CI =& get_instance();
	}

	public static function set_module($module) {
		self::$module = $module;
	}

	public static function set_header($header) {
		self::$header = $header;
	}

	public static function set_header_script($script) {
		$ci =& get_instance();
		self::$data["header_script"] = $this->load->view($script, "", TRUE);
	}

	private static function get_access_menu() {
		$ci =& get_instance();

		/*
		SELECT menu_id
		FROM permissions
		JOIN groups ON groups.id = permissions.group_id
		JOIN user_group ON user_group.group_id = groups.id
		WHERE user_id = 76
		*/
		$session = $ci->session->userdata("logged_in");

		$ci->load->model("Permissions_model");

		$menu = $ci->Permissions_model->select("GROUP_CONCAT(permissions.menu_id) as menu")
								->join("groups", "groups.id = permissions.group_id")
								->join("user_group", "user_group.group_id = groups.id")
								->find_by("user_id", $session->id);

		return $menu;
	}

	public static function set_menu() {
		$ci =& get_instance();

		$ci->load->model("Menu_model");

		/*
		select menu_new.id, menu_new.menu, menu_new.link, menu_new.icon, child.id AS child_id, child.menu AS child_menu, child.link AS child_link, child.icon AS child_icon, child.weight AS child_weight
		from menu
		left join menu child on menu_new.id = child.parent_id
		where menu_new.module = "sdm" AND menu_new.parent_id = 0
		order by menu_new.weight asc, menu_new.id asc, child.weight asc 
		*/

		$condition = array(
			"menu_new.parent_id"	=> 0,
			"menu_new.module"		=> self::$module
			);

		/*
		SELECT menu_new.id, menu_new.menu, menu_new.link, menu_new.icon, child.id AS child_id, child.menu AS child_menu, child.link AS child_link, child.icon AS child_icon, child.weight AS child_weight
		FROM menu
		LEFT JOIN menu child ON menu_new.id = child.parent_id
		WHERE menu_new.parent_id = 0 AND menu_new.module = "sdm"
		ORDER BY menu_new.weight ASC, menu_new.id ASC, child.weight ASC 
		*/

		$menu = $ci->Menu_model->select("menu_new.id, menu_new.menu, menu_new.link, menu_new.icon, child.id AS child_id, child.menu AS child_menu, child.link AS child_link, child.icon AS child_icon, child.weight AS child_weight")
												->join("menu_new child", "menu_new.id = child.parent_id", "left")
												->where($condition)
												->order_by("menu_new.weight", "ASC")
												->order_by("menu_new.id", "ASC")
												->order_by("child.weight", "ASC")
												->find_all();

		$access = self::get_access_menu();
		$access = explode(",",$access->menu);

		$array = array();

		if(is_array($menu) || !empty($menu)) {
			foreach($menu as $value) {
				if(in_array($value->id, $access) || in_array($value->child_id, $access)) {
					if(!isset($array[$value->menu]) && !$value->child_id) {
						$array[$value->menu] = array(
							"link"	=> $value->link,
							"icon"	=> $value->icon
						);
					}
					if($value->child_id) {
						if(!isset($array[$value->menu]["icon"])) {
							$array[$value->menu]["icon"] = $value->icon;
						}
						$array[$value->menu]["sub"][$value->child_menu] = array(
							"link"	=> $value->child_link,
							"icon"	=> $value->child_icon
						);
					}
				}
			}
		}

		self::$data["new_menu"] = $array;
		self::$data["menu"] = $menu;
	}

	public static function set_body($body, $data = null) {
		self::$body = $body;
		self::$passing_parameter = $data;
	}

	public static function set_footer($footer) {
		self::$footer = $footer;
	}

	public static function set_footer_script($script) {
		$ci =& get_instance();
		self::$data["footer_script"] = $ci->load->view($script, "", TRUE);
	}

	public static function set_title($title, $subtitle = null) {
		self::$data["title"] = $title;
		self::$data["subtitle"] = $subtitle;
	}

	public static function set_message($type, $message) {
		$ci =& get_instance();

		$msg = array(
			$type	=> $message
		);

		$ci->session->set_flashdata("message", $msg);
	}

	public static function render() {
		$ci =& get_instance();
		self::$data["message"] = $ci->session->flashdata("message");
		$ci->load->view(self::$header, self::$data);
		
		if(self::$body != null) {
			$ci->load->view(self::$body, self::$passing_parameter);
		}

		$ci->load->view(self::$footer);
	}

	public static function load_modal($modal, $data = null) {
		$ci =& get_instance();
		$ci->load->view($modal, $data);
	}
}
?>