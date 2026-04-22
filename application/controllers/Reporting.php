<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reporting extends CI_Controller {
	
	function __construct(){
		parent::__construct();
		$this->load->helper('url');
		$this->load->library('session');
		$this->load->library('user_agent');

		$baseurl = base_url();
		$this->load->model('Reporting_model');
	}

	public function index(){
		show_404();	
	}

	public function survey_list(){
		if(($this->session->userdata('login_id') == '')) {
			$baseurl = base_url();
			redirect($baseurl);
		}
		
		$get_usersurvey = $this->Reporting_model->get_survey();

		$this->load->view('header');
		$this->load->view('sidebar');
		$this->load->view('reporting/survey_list', $get_usersurvey);
		$this->load->view('footer');
	}

	public function upload_data(){
		if(($this->session->userdata('login_id') == '')) {
			$baseurl = base_url();
			redirect($baseurl);
		}

		$form_id = $this->uri->segment(3);

		if($form_id == '' || $form_id == NULL){
			show_404();
		}
 
		$survey_details = $this->Reporting_model->get_survey_details($form_id);

		$this->load->view('header');
		$this->load->view('sidebar');
		$this->load->view('reporting/upload_data', $survey_details);
		$this->load->view('footer');
	}
}