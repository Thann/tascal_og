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
		$data["load_js"] = array(
			"libs/jquery-1.8.1.min.js", //1.8.2 causes problems with fullcal
			//"libs/jquery-ui-1.8.23.custom.min.js",
			"libs/jquery-ui-1.9.0.custom.min.js",
			"libs/jquery.form.js",
			"libs/fullcalendar.min.js",
			"libs/tiny_mce/jquery.tinymce.js",
			"libs/jquery.miniColors.min.js",
			//"libs/gcal.js",
			"calendar.js"
		);
		$data["load_css"] = array(
			"libs/fullcalendar.css",
			"libs/ui-lightness/jquery-ui-1.9.0.custom.min.css",
			"libs/jquery.miniColors.css",
			"calendar.css"
		);

		$data["events"] = "";
		$data["default_color"] = '#3366CC';
		$data["tasks"] = $this->user->get_tasks($data["user"]->uid);

		$data["header"] = $this->load->view('header_view', $data, TRUE);

		$this->load->view('calendar_view', $data );
	}

	public function fetchCal() {
		$user_id = $this->session->userdata('uid');
		$events = $this->user->get_events($user_id);

		foreach ($events as $e) {
			$task = $this->user->get_task($e->tid);
			$e->title = $task->title;
			if ($e->desc == NULL)
				$e->desc = "";
			//~ else {
				//~ $e->title .= "\n------------\n" ;
				//~ $e->title .= $e->desc;
			//~ }
			if ($e->allDay == 'true')
				$e->allDay = true;
			else
				$e->allDay = false;
			$e->color = $task->color;
		}
		echo json_encode($events);
	}

	public function addTask() {
		$ret = $this->input->post();
		$ret["uid"] = $this->session->userdata('uid');

		$task = $this->user->add_task($ret);
		if ($task['status'])
			echo json_encode(array('status'=>true, 'task'=>$task['task']));
		else
			echo json_encode(array('status'=>false, 'msg'=>'task addition failed!'));
	}

	public function updateTask() {
		$ret = $this->input->post();

		if ($this->user->update_task($ret))
			echo json_encode(array('status'=>true));
		else
			echo json_encode(array('status'=>false));
	}

	public function addEvent() {
		$ret = $this->input->post();

		if ($ret['eid'] == 0) {
			unset($ret['eid']);
			$ret['uid'] = $this->session->userdata('uid');
			$this->user->add_event($ret);
			echo json_encode($ret);
		}
		else
			if ($this->user->update_event($ret))
				echo json_encode(array('status'=>true));
			else
				echo json_encode(array('status'=>false));
	}
}

/* End of file calendar.php */
/* Location: ./application/controllers/calendar.php */
