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
		if( $this->user->logged_in() )
			redirect( "calendar" );
		else
		{
			$this->config->load('branding');
			$data = array();
			$data["title"] = "Tascal Login";
			$data["load_js"] = array(
				$this->config->item('js_jquery'),
				$this->config->item('js_jquery-ui'),
				"libs/jquery.form.js",
				"common.js",
				"login.js"
			);
			$data["load_css"] = array(
				$this->config->item('css_jquery-ui'),
				"login.css"
			);

			$this->load->view('login_view', $data);
		}
	}

	public function validate() {
		$data = $this->input->post();

		if( $this->user->login($data))
			echo json_encode(array('status'=>true,'url'=>site_url('calendar')));
		else
			echo json_encode(array('status'=>false,'msg'=>'Bad Username or Password!'));
	}

	public function logout() {
		$this->user->logout();
		redirect( "login" );
	}

	public function create() {
		$data = $this->input->post();

		if ($this->user->create_new($data)){
			$data['remember'] = false;
			$this->user->login($data);
			echo json_encode(array('status'=>true,'url'=>site_url('calendar')));
		}
		else
			echo json_encode(array('status'=>false,'msg'=>'username taken!'));
	}

	public function update() {
		$data = $this->input->post();

		if ($this->user->update_info($data)){
			echo json_encode(array('status'=>true,'msg'=>'successfully updated!'));
		}
		else
			echo json_encode(array('status'=>false,'msg'=>'username taken!'));
	}
}

/* End of file login.php */
/* Location: ./application/controllers/login.php */
