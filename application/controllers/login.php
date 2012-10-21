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
			$data = array();
			$data["title"] = "Tascal Login";
			$data["load_js"] = array(
				"libs/jquery-1.8.1.min.js", //1.8.2 causes problems with fullcal
				"libs/jquery-ui-1.9.0.custom.min.js",
				"libs/jquery.form.js",
				"login.js"
			);
			$data["load_css"] = array(
				"libs/ui-lightness/jquery-ui-1.9.0.custom.min.css",
				"login.css"
			);

			$this->load->view('login_view', $data);
		}
	}

	public function validate() {
		//$ret = $this->input->post();
		//~ echo json_encode($this->input->post( "remember" ));

		if( $this->user->login())
			echo site_url('calendar');
			////~ redirect('calendar');
			////~ echo json_encode($ret);
			////~ echo 1;
		else
			echo "fail";
			////~ redirect('');
			////~ echo 0;
			////~ echo json_encode($ret);
			////~ return "fail";
	}

	public function logout() {
		$this->user->logout();
		redirect( "login" );
	}

	public function create() {
		//#TODO: make a form and pull this info.
		$data = array(
			'uname' => 'dme2',
			'rname' => 'testers real name',
			'email' => 'tester@nowhere.com',
			'passwd' => 'test'
		);
		if ($this->user->create_new($data))
			echo json_encode(array('status'=>true));
		else
			echo json_encode(array('status'=>false, 'msg'=>'username taken!'));
	}
}

/* End of file login.php */
/* Location: ./application/controllers/login.php */
