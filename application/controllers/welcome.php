<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index() {
		$this->load->model('user');
		
		$data = array();
		$data["java_vars"] = $this->user->logon_user();
		log_message('info', "blah blah");
		
		$data["application_js"] = "<script type='text/javascript' src='" . js_url() . "calendar.js' ></script>";
		$data["application_css"] = "<link rel='stylesheet' type='text/css' href='".css_url()."calendar.css'/>\n";
		$data["title"] = "lame";
		
		$data["tasks"] = array(
			array("id"=>4,"name"=>"taskone","desc"=>"task1 desc"), 
			array("id"=>7,"name"=>"task2","desc"=>"task2desc")
		);
		//~ $data["java_vars"] = array("one", "two", "three");
		
		$this->load->view('calendar', $data );
		//~ $this->load->view('welcome_message', $data );
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
