<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class xApi extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('user');
	}

	function index() {
		echo json_encode(array('status'=>false,'msg'=>"Invalid URL"));
	}

	function ical() {
		$type = $this->uri->segment(3);
		$id = $this->uri->segment(4);
		$hash = $this->uri->segment(5);
		$ret = $this->user->get_group_tasks($id);
		//~ echo json_encode($ret);
		if (!$ret) {
			echo json_encode(array('status'=>false,'msg'=>"Invalid URL params"));
			return false;
		}

		$this->output->set_header("Connection:close");
		$this->output->set_content_type('text/plain');
		$this->load->view('ical_view', array('group'=>$ret) );
		//~ echo json_encode(array('status'=>false,'msg'=>"Invalid URL params"));
	}
}
