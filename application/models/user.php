<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Model {

	function __construct() {
		// Call the Model constructor
		parent::__construct();
	}

	function login($data){
		$query = $this->db->get_where('users',array('uname'=>$data["uname"], 'passwd'=>sha1($data["passwd"])));
		
		if( $query->num_rows() > 0 ) {
			$user = $query->row();
			
			$sesh = array(
				'uid' 	=> $user->uid,
				'uname'	=> $user->uname,
				'logged_in' => TRUE
			);
			$this->session->set_userdata($sesh);

			//if the user requested to be remembered
			if( $data["remember"] ) {
			//load string heler
			$this->load->helper('string');

			//generate random unique string
			$token = random_string('unique');

			//update the table
			$this->db->where('uid', $user->uid);
			$this->db->update('users', array( "token" => $token )); 

			//set a cookie with the remember me token and the user id
			$this->input->set_cookie( "token", $token, 60*60*24*15 );
			$this->input->set_cookie( "uid", $user->uid, 60*60*24*15 );
			}

			return true;
		}
		return false;
	}

	function logout() {
		$this->load->helper('cookie');
		//set the user as logged out
		$this->session->set_userdata( array( 'logged_in' => FALSE ) );

		//unset the cookies
		delete_cookie( "uid" );
		delete_cookie( "token" );
	}

	function logged_in() {
		//load the cookie helper
		$this->load->helper('cookie');

	if( $this->session->userdata('uid') && $this->session->userdata('uname') ) {
		//check the database for the user
		$session_query = $this->db->get_where('users',array(
			"uid"=>$this->session->userdata('uid'),
			"uname"=>$this->session->userdata('uname')
		));

		//if we found someone, and the user logged in session data is set to true, then the user is logged in
		if( $session_query->num_rows() > 0 && $this->session->userdata('logged_in') )
		return true;
	}

	//if sessions were not successful, try cookies
	if( get_cookie('uid') && get_cookie('token') ) {	
		//check if user exists with id and remember me token
		$cookie_query = $this->db->get_where("users",array(
			"uid"   => get_cookie('uid'),
			"token" => get_cookie('token')
		));

		//if we found a user
		if( $cookie_query->num_rows() > 0 ) {
		//get the user row
		$user = $cookie_query->row();

		//load the string helper
		$this->load->helper('string');

		//generate random unique string for new remember me token
		$token = random_string('unique');

		//update table
		$this->db->where('uid', $user->id);
		$this->db->update('user', array( "token" => $token )); 

		//set new cookie data to reflect new rmember me token
		$this->input->set_cookie( "token", $token, 60*60*24*15 );
		$this->input->set_cookie( "uid", $user->id, 60*60*24*15 );

		//setup session data to reflect logged in
		$sesh = array
		(
			'uid' => get_cookie('uid'),
			'uname'	=> $user->uname,
			'logged_in' => TRUE
		);
		$this->session->set_userdata($sesh);

		return true;
		}
	}
	return false;
	}

	function get_row($uname = NULL) {
		if ($uname == NULL)
			$uname = $this->session->userdata('uname');

		$query = $this->db->get_where('users',array('uname' => $uname));
		$query = $query->result();
		return $query[0];
	}

	function get_tasks($uid) {
		$groups = $this->db->get_where('members',array('uid' => $uid));
		$groups = $groups->result();
		$gids = array();
		foreach ($groups as $g)
			$gids[] = $g->gid;

		$this->db->where_in('gid',$gids);
		$query = $this->db->get('tasks');
		$group_tasks = $query->result();

		$this->db->where('gid',0);
		$this->db->where('uid',$uid);
		$query = $this->db->get('tasks');
		$my_tasks = $query->result();
		$ret = array();
		$ret[0]['group'] = (object)array('gid'=>0,'owner'=>0,'settings'=>0,'name'=>'My Tasks');
		$ret[0]['tasks'] = $my_tasks;
		$index = 1;
		foreach ($groups as $g) {
			$gg = $this->db->get_where('groups',array('gid'=>$g->gid));
			$ret[$index]['group'] = $gg->result()[0];
			$ret[$index]['tasks'] = array();
			foreach ($group_tasks as $t) {
				if ($t->gid == $g->gid)
					$ret[$index]['tasks'][] = $t;
			}
			$index ++;
		}
		//~ foreach ($group_tasks as $g) {
			//~ foreach 
		//~ }
		
		//~ foreach ($groups)
		
		return $ret;
		//~ $query = $query->result();
		//~ return $query;
		//~ return $gids;
		//~ return $group_tasks[0]->gid;
	}

	function get_task($tid){
		$query = $this->db->get_where('tasks',array('tid' => $tid));
		$query = $query->result();
		return $query[0];
	}

	function add_task($data) {
		if ($this->db->insert('tasks',$data)) {
			$tid = $this->db->insert_id();
			$task = $this->get_task($tid);
			return array('status'=>true, 'task'=>$task);
		}
		else
			return 0;
	}

	function update_task($data) {
		$this->db->where('tid', $data['tid']);
		unset($data['tid']);
		if ($this->db->update('tasks',$data))
			return true; 
		else
			return false;
	}

	function get_events($uid) {
		$query = $this->db->get_where('events',array('uid' => $uid));
		return $query->result();;
	}

	function add_event($data) {
		if ($this->db->insert('events',$data))
			return $this->db->insert_id(); 
		else
			return false;
	}
	
	function update_event($data) {
		$this->db->where('eid', $data['eid']);
		if ($this->db->update('events',$data))
			return true; 
		else
			return false;
	}

	function create_new($data) {
		$query = $this->db->get_where('users',array('uname' => $data["uname"]));
		if( $query->num_rows() != 0 )
			return false;

		$data["passwd"] = sha1($data["passwd"]);
		$this->db->insert('users',$data);

	return true;
	}
}

/* End of file user.php */
/* Location: ./application/models/user.php */
