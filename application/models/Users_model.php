<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users_model extends MY_Model {

	protected $table_name 	= "users_admin";
    protected $key          = 'user_id';
    protected $set_created  = false;
    protected $set_modified = false;
    protected $soft_deletes = false;
    protected $date_format  = 'datetime';
}