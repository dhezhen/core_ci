<?php 
defined ("BASEPATH") OR exit ('No direct script acces allowed');

class Advertise_model extends MY_MODEL {
	protected $table_name		="bijbmobile_advertise";
	protected $key				= 'bmad_id';
	protected $set_created		= false;
	protected $set_modified		= false;
	protected $soft_deletes		= false;
	protected $date_format		= 'datetime';
	}
?>