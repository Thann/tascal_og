<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Calendar extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('user');
	}

	public function index() {
		$data = array();
		$data["title"] = "Tascal";
		$data["user"] = $this->user->get_user_row("nobody");
		$data["application_js"] = "<script type='text/javascript' src='" . js_url() . "calendar.js' ></script>";
		$data["application_css"] = "<link rel='stylesheet' type='text/css' href='".css_url()."calendar.css'/>\n";
		
		$data["tasks"] = $this->user->get_tasks($data["user"]->uid);
		$data["events"] = "";

		//~ $data["js_vars"] = array("one", "two", "three");
		$data["js_vars"] = $data["tasks"];

		$this->load->view('calendar', $data );
	}
	
	public function addTask() {
		$ret = $this->input->post();
		$this->user->add_task($ret);
		$ret = "yay";
		echo json_encode($ret);
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
