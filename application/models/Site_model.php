<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Site_model extends MY_Model {

	protected $table_name 	= "bijbmobile_site";
    protected $key          = 'name';
    protected $set_created  = false;
    protected $set_modified = false;
    protected $soft_deletes = false;
    protected $date_format  = 'datetime';
}
?>