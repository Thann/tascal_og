<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Manage extends CI_Controller {

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
		
		$data["header"] = $this->load->view('header_view', $data, TRUE);
		$data["footer"] = $this->load->view('footer_view', $data, TRUE);
		
		$this->load->view('manage_view', $data );
	}

}

/* End of file manage.php */
/* Location: ./application/controllers/manage.php */
