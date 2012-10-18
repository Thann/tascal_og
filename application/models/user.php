<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class user extends CI_Model {
	
	function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
    
    function logon_user() {

	$qret = array();
	$query = $this->db->get('users');
	//~ foreach ($query->result() as $row)
	//~ {
	    //~ $qret[] = $row->id;
	//~ }

	return $query;
    }
}
