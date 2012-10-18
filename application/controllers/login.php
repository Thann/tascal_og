<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {
	
	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/login
	 *	- or -  
	 * 		http://example.com/index.php/login/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	 public function __construct() {
		parent::__construct();
		$this->load->model('user');
	}
	
	public function index() {
		$data = array();
		$data["title"] = "Tascal Login";
		$data["application_js"] = "<script type='text/javascript' src='" . js_url() . "login.js' ></script>";
		$data["application_css"] = "<link rel='stylesheet' type='text/css' href='".css_url()."login.css'/>\n";
		//~ redirect('calendar');
		$this->load->view('login_view', $data);
	}
	
	public function validate(){
		$ret = $this->input->post();
		if( $this->user->login($ret) )
			//echo site_url('calendar');
			redirect('calendar');
		else
			//echo "fail";
			redirect('');
	}
}

/* End of file login.php */
/* Location: ./application/controllers/login.php */
