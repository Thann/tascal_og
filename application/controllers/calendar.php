<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Calendar extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('user');
		
		if( !$this->user->logged_in() )
			redirect( "login" );
	}

	public function index() {
		$this->config->load('branding');
		$data = array();
		$data["location"] = 'calendar';
		$data["title"] = "Tascal";
		$data["user"] = $this->user->get_row();
		$data["load_js"] = array(
			$this->config->item('js_jquery'),
			$this->config->item('js_jquery-ui'),
			"libs/fullcalendar.min.js",
			"libs/tiny_mce/jquery.tinymce.js",
			"libs/jquery.miniColors.min.js",
			//"libs/gcal.js",
			"calendar.js"
		);
		$data["load_css"] = array(
			$this->config->item('css_jquery-ui'),
			"libs/fullcalendar.css",
			"libs/jquery.miniColors.css",
			"calendar.css"
		);

		$data["default_color"] = '#3366CC';
		$data["tasks"] = $this->user->get_tasks($data["user"]->uid);

		$data["mask"] = array(
			'showEventDesc' => 1,
		);

		$data["events"] = array();
		foreach ($data["tasks"] as $group) {
			foreach ($group['tasks'] as $task) {
				$events = $this->user->get_task_events($task->tid);
				foreach ($events as $e) {
					$e->title = $task->title;
					if ($e->desc == NULL)
						$e->desc = "";
					//else {
					//	$e->title .= "\n------------\n" ;
					//	$e->title .= $e->desc;
					//}
					if ($e->allDay == 'true')
						$e->allDay = true;
					else
						$e->allDay = false;
					$e->color = $task->color;
					$e->gid = $task->gid;
				}
				$data["events"] = array_merge($data["events"],$events);
			}
		}

		$data["header"] = $this->load->view('header_view', $data, TRUE);
		$data["footer"] = $this->load->view('footer_view', NULL, TRUE);

		$this->load->view('calendar_view', $data );
	}

	public function addTask() {
		$ret = $this->input->post();
		$ret["uid"] = $this->session->userdata('uid');
		$ret["desc"] = "<p><br></p>";
		$ret["settings"] = 0;

		$task = $this->user->add_task($ret);
		echo json_encode($task);
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
			$ret = $this->user->add_event($ret);
			echo json_encode($ret);
		}
		else
			if ($this->user->update_event($ret))
				echo json_encode(array('status'=>true));
			else
				echo json_encode(array('status'=>false));
	}

	public function rmTask() {
		$tid = $this->input->post();
		$ret = $this->user->rm_task($tid);
		echo json_encode($ret);
	}

	public function rmEvent() {
		$eid = $this->input->post();
		$ret = $this->user->rm_event($eid);
		echo json_encode($ret);
	}
}

/* End of file calendar.php */
/* Location: ./application/controllers/calendar.php */
