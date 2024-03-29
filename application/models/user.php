<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Model {

	function __construct() {
		// Call the Model constructor
		parent::__construct();
		$this->expiration_duration = 60*60*24*15; //15 days
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
			if( $data["remember"] == 'true') {
				//load string heler
				$this->load->helper('string');

				//generate random unique string
				$token = random_string('unique');

				//update the table
				$this->db->where('uid', $user->uid);
				$this->db->update('users', array( "token" => $token ));

				//set a cookie with the remember me token and the user id
				$this->input->set_cookie( "token", $token, $this->expiration_duration );
				$this->input->set_cookie( "uid", $user->uid, $this->expiration_duration );
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
			$this->db->where('uid', $user->uid);
			$this->db->update('users', array( "token" => $token )); 

			//set new cookie data to reflect new rmember me token
			$this->input->set_cookie( "token", $token,$this->expiration_duration );
			$this->input->set_cookie( "uid", $user->uid, $this->expiration_duration );

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

	function update_info($data) {
		$this->db->where('uid', $data['uid']);
		if (isset($data["passwd"]))
			$data["passwd"] = sha1($data["passwd"]);
		unset($data['uid']);
		if ($this->db->update('users',$data))
			return true; 
		else
			return false;
	}

	function get_row($uid = NULL) {
		if ($uid == NULL)
			$uid = $this->session->userdata('uid');

		$query = $this->db->get_where('users',array('uid' => $uid));
		$query = $query->result();
		return $query[0];
	}

	function get_user($uname) {
		$query = $this->db->get_where('users',array('uname'=>$uname));
		if( $query->num_rows() > 0 ) {
			$query = $query->result();
			return array('status'=>true,'user'=>$query[0]);
		}
		return array('status'=>false);
	}

	function search_users($term) {
		$this->db->like('rname', $term, 'both');
		$this->db->or_like('uname', $term, 'after');
		$query = $this->db->get('users');
		$query = $query->result();
		return $query;
	}

	function add_member($data) {
		//check for membership
		$query = $this->db->get_where('members',array('gid'=>$data["gid"],'uid'=>$data["uid"]));
		if( count($query->result()) > 0 )
			return array('status'=>false,'msg'=>"User Is Already a Member",'result'=>count($query->result()));

		if ($this->db->insert('members',$data)) {
			$mid = $this->db->insert_id();
			$member = $this->db->get_where('members',array('mid'=>$mid));
			$member = $member->result();
			return array('status'=>true, 'member'=>$member[0]);
		}
		return array('status'=>false,'msg'=>"Unknown Error");
	}

	function rm_member($data) {
		$user = $this->db->get_where('members',array('gid'=>$data["gid"],'mid'=>$data["mid"]));
		$user = $user->result();
		$user = $user[0];
		$group = $this->db->get_where('groups',array('gid'=>$data["gid"]));
		$group = $group->result();
		$group = $group[0];
		$current_user = $this->session->userdata('uid');
		if ($group->owner == $user->uid)
			return array('status'=>false, 'msg'=>"The owner cant leave the group.");
		if ($current_user != $group->owner && $current_user != $user->uid)
			return array('status'=>false, 'msg'=>"Only the owner can kick other users.");
		if ($this->db->delete('members',array('mid'=>$data["mid"])))
			return array('status'=>true);
		return array('status'=>false, 'msg'=>"Error while removing member.");
	}

	function get_groups($uid) {
		$groups = $this->db->get_where('members',array('uid' => $uid));
		$groups = $groups->result();
		
		$gids = array();
		foreach ($groups as $g)
			$gids[] = $g->gid;

		if (isset($gids[0]))
			$this->db->where_in('gid',$gids);
		$groups = $this->db->get('groups');
		$groups = $groups->result();
		foreach ($groups as $g) {
			$query = $this->db->get_where('members',array('gid' => $g->gid));
			$g->members = $query->result();
			foreach ($g->members as $m) {
				$query = $this->db->get_where('users',array('uid' => $m->uid));
				$query = $query->result();
				$query = $query[0];
				unset($query->passwd);
				unset($query->token);
				$m->user = $query;
			}
		}
		return $groups;
	}

	function add_group($data) {
		if ($this->db->insert('groups',$data)) {
			$gid = $this->db->insert_id();
			$group = $this->db->get_where('groups',array('gid' => $gid));
			$group = $group->result();
			$group = $group[0];
			$memData = array('gid'=>$gid,'uid'=>$group->owner,'perms'=>0);
			$member = $this->add_member($memData);
			//~ return array('status'=>false, $member);
			if ($member["status"]) {
				$member = $member["member"];
				$user = $this->db->get_where('users',array('uid'=>$group->owner));
				$user = $user->result();
				$user = $user[0];
				unset($user->passwd);
				unset($user->token);
				$member->user = $user;
				$group->members = array($member);
				return array('status'=>true, 'group'=>$group);
			}
		}
		return array('status'=>false);
	}

	function update_group($data) {
		$this->db->where('gid', $data['gid']);
		unset($data["gid"]);
		if ($this->db->update('groups',$data))
			return array('status'=>true); 
		else
			return array('status'=>false, 'msg'=>"Error Updating Group");
	}

	function rm_group($gid) {
		$group = $this->db->get_where('groups',array('gid' => $gid));
		$group = $group->result();
		$uid = $this->session->userdata('uid');
		if ($uid == $group[0]->owner) {
			$tasks = $this->db->get_where('tasks',array('gid' => $gid));
			foreach ($tasks->result() as $t) {
				$ret = $this->rm_task(array('tid'=>$t->tid));
				if (!$ret["status"])
					return $ret;
			}
			if ($this->db->delete('groups',array('gid'=>$gid)))
				if ($this->db->delete('members',array('gid'=>$gid)))
					return array('status'=>true);
		}
		else 
			return array('status'=>false,'msg'=>"Only the owner can delete a group.");
		return array('status'=>false, 'msg'=>"error deleting group");
	}

	function get_group_tasks($gid) {
		//#TODO: remove unsets
		$group = $this->db->get_where('groups',array('gid' => $gid));
		$group = $group->result();
		if (!sizeof($group))
			return false;
		$group = $group[0];
		$query = $this->db->get_where('tasks',array('gid' => $gid));
		$group->tasks = $query->result();
		$events = array();
		foreach ($group->tasks as $t) {
			unset($t->desc);
			$query = $this->db->get_where('events',array('tid' => $t->tid));
			$t->events = $query->result();
			foreach ($t->events as $e) {
				unset($e->desc);
			}
		}
		return $group;
	}

	function get_tasks($uid) {
		$groups = $this->db->get_where('members',array('uid' => $uid));
		$groups = $groups->result();
		$gids = array();
		foreach ($groups as $g)
			$gids[] = $g->gid;

		if (isset($gids[0]))
			$this->db->where_in('gid',$gids);
		$query = $this->db->get('tasks');
		$group_tasks = $query->result();

		$this->db->where('gid',0);
		$this->db->where('uid',$uid);
		$query = $this->db->get('tasks');
		$my_tasks = $query->result();
		$ret = array();
		$ret[0]['group'] = (object)array('gid'=>0,'owner'=>0,'settings'=>0,'title'=>'My Tasks');
		$ret[0]['tasks'] = $my_tasks;
		$index = 1;
		foreach ($groups as $g) {
			$gg = $this->db->get_where('groups',array('gid'=>$g->gid));
			$gg = $gg->result();
			$ret[$index]['group'] = $gg[0];
			$ret[$index]['tasks'] = array();
			foreach ($group_tasks as $t) {
				if ($t->gid == $g->gid)
					$ret[$index]['tasks'][] = $t;
			}
			$index ++;
		}
		return $ret;
	}

	function get_task($tid){
		$query = $this->db->get_where('tasks',array('tid' => $tid));
		$query = $query->result();
		return $query[0];
	}

	//#TODO: make it so the (members) group perms are checked before completing 'add_task' or 'rm_task'
	function add_task($data) {
		if ($this->db->insert('tasks',$data)) {
			$tid = $this->db->insert_id();
			$task = $this->get_task($tid);
			return array('status'=>true, 'task'=>$task);
		}
		else
			return array('status'=>false);
	}

	function rm_task($tid) {
		if ($this->db->delete('tasks',$tid))
			if ($this->db->delete('events',$tid))
				return array('status'=>true);
		return array('status'=>false,'msg'=>"error deleting task");
	}

	function update_task($data) {
		$this->db->where('tid', $data['tid']);
		unset($data['tid']);
		if ($this->db->update('tasks',$data))
			return true; 
		else
			return false;
	}

	function get_user_events($uid) {
		$query = $this->db->get_where('events',array('uid' => $uid));
		return $query->result();;
	}

	function get_task_events($tid) {
		$query = $this->db->get_where('events',array('tid' => $tid));
		return $query->result();;
	}

	function add_event($data) {
		if ($this->db->insert('events',$data)){
			$eid = $this->db->insert_id();
			$event = $this->db->get_where('events',array('eid'=>$eid));
			if( $event->num_rows() != 0 ) {
				$event = $event->result();
				return array('status'=>true, 'event'=>$event[0]);
			}
			return array('status'=>false, 'msg'=>"Error retrieving added event.");
		}
		else
			return array('status'=>false, 'msg'=>"Error adding event.");
	}

	function rm_event($eid) {
		if ($this->db->delete('events',$eid))
			return array('status'=>true);
		else
			return array('status'=>false);
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
