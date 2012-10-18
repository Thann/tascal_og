<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Calendar extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('user');
	}

	public function index() {
		
		$data = array();
		$data["user"] = $this->user->get_user_row("nobody");
		
		$data["application_js"] = "<script type='text/javascript' src='" . js_url() . "calendar.js' ></script>";
		$data["application_css"] = "<link rel='stylesheet' type='text/css' href='".css_url()."calendar.css'/>\n";
		$data["title"] = "lame";
		
		$data["tasks"] = $this->user->get_tasks($data["user"]->uid);

		//~ $data["java_vars"] = array("one", "two", "three");
		$data["java_vars"] = $data["tasks"];

		$this->load->view('calendar', $data );
		//~ $this->load->view('welcome_message', $data );
	}
	
	public function addTask() {
		$ret = $this->input->post();
		$this->user->add_task($ret);
		$ret = "yay";
		echo json_encode($ret);
	}
}

/* End of file calendar.php */
/* Location: ./application/controllers/calendar.php */
