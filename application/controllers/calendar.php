<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Calendar extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('user');
		
		if( !$this->user->logged_in() )
			redirect( "login" );
	}

	public function index() {
		$data = array();
		$data["title"] = "Tascal";
		$data["user"] = $this->user->get_row();
		$data["application_js"] = "<script type='text/javascript' src='" . js_url() . "calendar.js' ></script>";
		$data["application_css"] = "<link rel='stylesheet' type='text/css' href='".css_url()."calendar.css'/>\n";
		
		$data["tasks"] = $this->user->get_tasks($data["user"]->uid);
		$data["events"] = "";

		//~ $data["js_vars"] = array("one", "two", "three");
		$data["js_vars"] = "";//$data["tasks"];
		$data["header"] = $this->load->view('header_view', $data, TRUE);
		
		$this->load->view('calendar_view', $data );
	}
	
	
	public function fetchCal() {
		$user_id = $this->session->userdata('uid');
		//~ $tasks = $this->user->get_tasks($user_id);
		//~ foreach ($tasks as $t) {
		//~ $events[0]->title = "woot woot";
		$events = array(array('title'=>"test", 'start'=>'2012-10-19T10:22:30Z', 'desc'=>$user_id));
		echo json_encode($events);
	}
	
	public function addTask() {
		$ret = $this->input->post();
		$ret["uid"] = $this->session->userdata('uid');
		if ($this->user->add_task($ret))
			echo json_encode(array('status'=>true));
		else
			echo json_encode(array('status'=>false, 'msg'=>'task addition failed!'));
	}
	public function addEvent() {
		$ret = $this->input->post();
		//~ $this->user->add_event($ret);
		$ret = "yay";
		echo json_encode($ret);
	}
}

/* End of file calendar.php */
/* Location: ./application/controllers/calendar.php */
