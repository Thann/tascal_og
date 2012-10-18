<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class user extends CI_Model {
	
	function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
    
    function get_user_row($uname) {
	$query = $this->db->get_where('users',array('uname' => $uname));
	$query = $query->result();
	return $query[0];
    }
    
    function get_tasks($uid){
	$query = $this->db->get_where('tasks',array('uid' => $uid));
	$query = $query->result();
	return $query;
    }
    
    function add_task($data)
    {
	//~ $data = array(
	    //~ 'uid'   =>$uid,
	    //~ 'title' =>$title,
	    //~ 'desc'  =>$desc
	//~ );
	$this->db->insert('tasks',$data);
    }
}
	//~ foreach ($query->result() as $row)
	//~ {
	    //~ //$qret[] = $row->id;
	//~ }
