<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kml_model extends CI_Model {

	public function __construct() {
		parent::__construct();
		$this->load->library('session');		
	}

	public function get_all_kml(){
		$this->db->select('file_name, measured_area');
		$this->db->where('kml_status', 1);
		return $kmls = $this->db->get('tbl_kmlfile')->result_array();
	}
}