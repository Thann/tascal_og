<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class External_Api extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('user');
	}

	function index() {
		$ret = $this->input->post();
		//~ echo json_encode(array('msg'=>"LAME"));
		log_message('debug',"wooters: ".json_encode($ret));
	}
}
