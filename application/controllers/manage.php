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
		$data["location"] = 'manage';
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
			"manage.js"
		);
		$data["load_css"] = array(
			"libs/fullcalendar.css",
			"libs/ui-lightness/jquery-ui-1.9.0.custom.min.css",
			"libs/jquery.miniColors.css",
			"manage.css"
		);

		$data["groups"] = $this->user->get_groups($data["user"]->uid);

		$data["header"] = $this->load->view('header_view', $data, TRUE);
		$data["footer"] = $this->load->view('footer_view', NULL, TRUE);
		
		$this->load->view('manage_view', $data );
	}

	public function test() {
		$data = array();
		$data["user"] = $this->user->get_row();
		$data["groups"] = $this->user->get_groups($data["user"]->uid);
		
		echo json_encode($data["groups"]);
	}

}

/* End of file manage.php */
/* Location: ./application/controllers/manage.php */
