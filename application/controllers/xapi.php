<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class xApi extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('user');
	}

	function index() {
		$ret = $this->input->post();
		echo json_encode(array('status'=>false,'msg'=>"LAME"));
		//~ log_message('debug',"wooters: ".json_encode($ret));
	}

	function ical() {
		$type = $this->uri->segment(3);
		$id = $this->uri->segment(4);
		$hash = $this->uri->segment(5);
		$ret = $this->user->get_group_tasks($id);
		echo json_encode(array('status'=>true,'type'=>$type,'id'=>$id,'hash'=>$hash,'group'=>$ret));
		//~ echo json_encode(array('status'=>false,'msg'=>"Invalid URI"));
	}
}
