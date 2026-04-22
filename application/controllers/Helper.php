<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Helper extends CI_Controller {
	
	function _construct() {
		parent::_construct();
		$this->load->helper('url');
	}
	
	public function index()
	{
		show_404();	
	}

	public function all_divisions()
	{
		$this->load->model('Helper_model');
		$all_divisions = $this->Helper_model->all_divisions();

		echo json_encode(array(
			'csrfName' => $this->security->get_csrf_token_name(),
			'csrfHash' => $this->security->get_csrf_hash(),
			'divisions' => $all_divisions
		));
		exit();
	}

	public function all_circles($DIV_CODE = NULL)
	{
		if($this->input->post('division')) {
			$DIV_CODE = $this->input->post('division');
			// $DIV_CODE = explode(',', $division);
		}
		$this->load->model('Helper_model');
		$all_circles = $this->Helper_model->all_circles($DIV_CODE);

		echo json_encode(array(
			'csrfName' => $this->security->get_csrf_token_name(),
			'csrfHash' => $this->security->get_csrf_hash(),
			'circles' => $all_circles
		));
		exit();
	}

	public function all_villages($CIR_CODE = NULL)
	{
		if($this->input->post('circle')) {
			$CIR_CODE = $this->input->post('circle');
			// $CIR_CODE = explode(',', $circle);
		}
		$this->load->model('Helper_model');
		$all_villages = $this->Helper_model->all_villages($CIR_CODE);

		echo json_encode(array(
			'csrfName' => $this->security->get_csrf_token_name(),
			'csrfHash' => $this->security->get_csrf_hash(),
			'villages' => $all_villages
		));
		exit();
	}
}