<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Settings extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('user');
		
		if( !$this->user->logged_in() )
			redirect( "login" );
	}

	public function index() {
		$this->config->load('branding');
		$data = array();
		$data["location"] = 'settings';
		$data["title"] = "Tascal";
		$data["user"] = $this->user->get_row();
		$data["load_js"] = array(
			$this->config->item('js_jquery'),
			$this->config->item('js_jquery-ui'),
			"libs/jquery.miniColors.min.js",
			//"libs/gcal.js",
			"common.js",
			"settings.js"
		);
		$data["load_css"] = array(
			$this->config->item('css_jquery-ui'),
			"libs/jquery.miniColors.css",
			"settings.css"
		);

		$data["default_color"] = '#3366CC';

		$data["groups"] = $this->user->get_groups($data["user"]->uid);

		$data["header"] = $this->load->view('header_view', $data, TRUE);
		$data["footer"] = $this->load->view('footer_view', NULL, TRUE);
		
		$this->load->view('settings_view', $data );
	}

	public function addGroup() {
		$ret = $this->input->post();
		$ret["owner"] = $this->session->userdata('uid');
		$ret["settings"] = 0;
		$group = $this->user->add_group($ret);
		echo json_encode($group);
	}

	public function searchMembers() {
		$ret = $this->input->post();
		//~ sleep(3);
		echo json_encode(array("status"=>true, "ret"=>$ret, "values"=>array(array("id"=>1,"uname"=>"uname1","rname"=>"rname1"),array("id"=>2,"uname"=>"uname2","rname"=>"rname1"))));
	}

	public function addMember() {
		$ret = $this->input->post();
		$query = $this->user->get_user($ret["uname"]);
		if ($query["status"]) {
			$data = array('gid'=>$ret["gid"],'uid'=>$query["user"]->uid,'perms'=>0);
			$member = $this->user->add_member($data);
			if ($member["status"]) {
				unset($query["user"]->passwd);
				unset($query["user"]->token);
				$member["member"]->user = $query["user"];
			}
			echo json_encode($member);
			return;
		}
		echo json_encode(array('status'=>false,'msg'=>"Invalid Username"));
	}

	public function updateGroup() {
		$data = $this->input->post();
		$ret = $this->user->update_group($data);
		echo json_encode($ret);
	}

	public function rmGroup() {
		$ret = $this->input->post();
		$ret = $this->user->rm_group($ret["gid"]);
		echo json_encode($ret);
	}

	public function rmMember() {
		$data = $this->input->post();
		$ret = $this->user->rm_member($data);
		echo json_encode($ret);
	}
}

/* End of file settings.php */
/* Location: ./application/controllers/settings.php */
