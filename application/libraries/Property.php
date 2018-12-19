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
class Property {

	public static $controller = "core";
	public static $name = "BIJB Information System";
	public static $abbr = "BIS:";
	public static $developer = "BIJB Dev";
	public static $developer_site = "http://bijb.co.id";
	public static $software_version = "1.0.0 (Stable)";

	public function __construct() {
		$ci =& get_instance();

		$ci->load->model("Site_model");
	}

	public static function get_name() {
		return self::$name;
	}

	public static function get_abbr() {
		return self::$abbr;
	}

}
?>