<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kml extends CI_Controller {
	
	function __construct(){
		parent::__construct();
		$this->load->helper('url');

		$baseurl = base_url();
		$this->load->model('Auth_model');
		$this->load->model('User_model');
		$this->load->model('Helper_model');
		// $session_allowed = $this->Auth_model->match_account_activity();
		// if(!$session_allowed) redirect($baseurl.'auth/logout');
	}

	public function index(){
		show_404();
	}

	public function view(){
		if(($this->session->userdata('login_id') == '')) {
			$baseurl = base_url();
			redirect($baseurl);
		}

		// $this->load->model('Dynamicmenu_model');
		// $main_menu = $this->Dynamicmenu_model->menu_details();
		// $main_menu = $this->security->xss_clean($main_menu);
		// $header_result = array('main_menu' => $main_menu);
		
		$result = array();
		
		$this->load->model('Kml_model');
		$all_kmls = $this->Kml_model->get_all_kml();
		$result['kmls'] = $this->security->xss_clean($all_kmls);

		$this->load->view('header');
		$this->load->view('sidebar');
		$this->load->view('kml/view', $result);
		$this->load->view('footer');
	}
}