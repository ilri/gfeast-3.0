<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Nopermission extends CI_Controller {
	
	function _construct(){
		parent::_construct();
		$this->load->helper('url');
		$this->load->library('session');
		$this->load->library('user_agent');
	}

	public function index(){
		$baseurl = base_url();
		if(($this->session->userdata('login_id') == null && $this->session->userdata('login_id') == '')) {
			redirect($baseurl);
		}

		$this->load->model('Dynamicmenu_model');
		$landing_url = $this->Dynamicmenu_model->get_landingpage();

		if($landing_url == 'nopermission') {
			$this->load->view('nopermission');
		} else redirect($baseurl.$landing_url);
	}
}