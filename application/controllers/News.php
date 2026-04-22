<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class News extends CI_Controller {
	
	function __construct(){
		parent::__construct();
		$this->load->helper('url');
		$this->load->library('session');
		$this->load->library('user_agent');

		$baseurl = base_url();
		$this->load->model('Auth_model');
		// $session_allowed = $this->Auth_model->match_account_activity();
		// if(!$session_allowed) redirect($baseurl.'auth/logout');
	}
	
	public function index()
	{
		show_404();	
	}

	public function view_news(){
		if(($this->session->userdata('login_id') == '')) {
			$baseurl = base_url();
			redirect($baseurl);
		}else{
			$data=array('user_id'=>$this->session->userdata('login_id'));
			
			$this->load->model('Dynamicmenu_model');
			$main_menu = $this->Dynamicmenu_model->menu_details();

			$this->db->select('project_id, project_name, project_description, added_datetime, status');
			$projects = $this->db->where('status', 1)->get('lkp_projects')->result_array();

			$all_news = array();
			$survey_ids = array();
			$limitStart=0;
			//get survey ids
			$this->db->select('id');
			$this->db->from('form');
			$this->db->where('status', 1);
			$this->db->where('type', 'Survey');
			$surveys = $this->db->get()->result_array();

			foreach ($surveys as $skey => $survey) {
				array_push($survey_ids, $survey['id']);
			}
			if(count($surveys) == 0){
				$survey_ids= array(0);
			}

			$this->db->distinct();
			$this->db->select('field.field_id,field.label,field.type');
			$this->db->from('form_field as field');
			$this->db->where('field.status', 1);
			$this->db->where_in('form_id', $survey_ids);
			$this->db->order_by('field.slno');
			$all_label_data = $this->db->get()->result_array();

			$this->db->distinct();
			$this->db->select('data.data_id, data.user_id, user.first_name, user.last_name, image.image as user_image, data.form_id, form.title, data.form_data, data.reg_date_time');
			$this->db->from('ic_form_data as data');
			$this->db->join('form as form', 'form.id = data.form_id');
			$this->db->join('tbl_users as user', 'user.user_id = data.user_id');
			$this->db->join('tbl_images as image', 'image.user_id = data.user_id');
			$this->db->where('data.data_status', 1);
			$this->db->where('image.status', 1);
			$this->db->where_in('data.form_id', $survey_ids);
			if(isset($_POST['project_id'])){
				if($_POST['project_id'] != 'all'){
					$this->db->where('data.project_id', $_POST['project_id']);
					if($_POST['country_id'] != 'all'){
						$this->db->where('data.country_id', $_POST['country_id']);
						if($_POST['state_id'] != 'all'){
							$this->db->where('data.state_id', $_POST['state_id']);
							if($_POST['district_id'] != 'all'){
								$this->db->where('data.district_id', $_POST['district_id']);
								if($_POST['centre_id'] != 'all'){
									$this->db->where('data.centre_id', $_POST['centre_id']);
								}
							}	
						}	
					}
				}
			}
			if(isset($_POST['limitStart'])){
				$this->db->limit(10, $_POST['limitStart']);
			}else{
				$this->db->limit(10, $limitStart);
			}
			// $this->db->limit(2,$limitStart);
			$this->db->order_by("data.reg_date_time", "desc");
			$form_data = $this->db->get()->result_array();

			foreach ($form_data as $fdkey => $fdata) {
				$all_news[$fdkey] = array(
					'feed_id' => $fdata['data_id'],
					'upload_date' => $fdata['reg_date_time'],
					'user_data' => array(),
					'post_image' => array(),
					'post_data' => array(),
					'post_location' => array()
				);

				//fetching respective user data
				array_push($all_news[$fdkey]['user_data'], array(
					'user_id' => $fdata['user_id'],
					'user_name' => $fdata['first_name'].' '.$fdata['last_name'],
					'user_image' => $fdata['user_image']
				));

				//fetching respective post image
				$this->db->distinct();
				$this->db->select('file.file_id, file.file_name, file.file_type');
				$this->db->from('ic_data_file as file');
				$this->db->join('ic_form_data as data', 'data.data_id = file.data_id');
				$this->db->where('file.data_id', $fdata['data_id']);
				if(isset($_POST['project_id'])){
					if($_POST['project_id'] != 'all'){
						$this->db->where('data.project_id', $_POST['project_id']);
						if($_POST['country_id'] != 'all'){
							$this->db->where('data.country_id', $_POST['country_id']);
							if($_POST['state_id'] != 'all'){
								$this->db->where('data.state_id', $_POST['state_id']);
								if($_POST['district_id'] != 'all'){
									$this->db->where('data.district_id', $_POST['district_id']);
									if($_POST['centre_id'] != 'all'){
										$this->db->where('data.centre_id', $_POST['centre_id']);
									}
								}	
							}	
						}
					}
				}
				$this->db->where('file.status', 1);
				$all_news[$fdkey]['post_image'] = $this->db->get()->result_array();

				//fetching respective post data
				foreach (json_decode($fdata['form_data']) as $fkey => $fdvalue) {
					if(is_array(json_decode($fdvalue))){
						$value =  implode( ", ",json_decode($fdvalue));
					} else {
						$value = $fdvalue == null ? 'N/A' : $fdvalue; 
					}

					foreach ($all_label_data as $key => $label) {
						if($fkey == 'field_'.$label['field_id']){
							array_push($all_news[$fdkey]['post_data'], array(
								'label' => $label['label'],
								'value' => $value,
							));
						}
					}
				}

				//fetching respective post location from ic_data_location using data_id
				$this->db->distinct();
				$this->db->select('loc.lat, loc.lng, loc.address, proj.project_name, country.name, state.state_name, district.district_name, centre.centre_name');
				$this->db->from('ic_data_location as loc');
				$this->db->join('ic_form_data as data', 'data.data_id = loc.data_id');
				$this->db->join('lkp_projects as proj', 'proj.project_id = loc.project_id');
				$this->db->join('lkp_centre as centre', 'centre.centre_id = loc.centre_id');
				$this->db->join('lkp_country as country', 'country.country_id = loc.country_id');
				$this->db->join('lkp_state as state', 'state.state_id = loc.state_id');
				$this->db->join('lkp_district as district', 'district.district_id = loc.district_id');
				$this->db->where('loc.data_id', $fdata['data_id']);
				if(isset($_POST['project_id'])){
					if($_POST['project_id'] != 'all'){
						$this->db->where('data.project_id', $_POST['project_id']);
						if($_POST['country_id'] != 'all'){
							$this->db->where('data.country_id', $_POST['country_id']);
							if($_POST['state_id'] != 'all'){
								$this->db->where('data.state_id', $_POST['state_id']);
								if($_POST['district_id'] != 'all'){
									$this->db->where('data.district_id', $_POST['district_id']);
									if($_POST['centre_id'] != 'all'){
										$this->db->where('data.centre_id', $_POST['centre_id']);
									}
								}	
							}	
						}
					}
				}
				$all_news[$fdkey]['post_location'] = $this->db->get()->row_array();
			}
			// echo "<pre>";
			// print_r($all_news); die();

			$result = array(
				'projects' => $projects,
				'all_news' => $all_news,
				'csrfName' => $this->security->get_csrf_token_name(),
				'csrfHash' =>  $this->security->get_csrf_hash()
			);

			if(isset($_POST['event']) && $_POST['event'] == 'change'){
				if($_POST['filter_type'] == 'project'){
					if($_POST['project_id'] != 'all'){
						//get country
						$this->db->select('country_id, name');
						$this->db->where('status', 1);
						$result['countries'] = $this->db->get('lkp_country')->result_array();
					} else {
						$result['countries'] = array();
					}
				}

				if($_POST['filter_type'] == 'country'){
					if($_POST['country_id'] != 'all'){
						//get states
						$this->db->select('state_id, state_name');
						$this->db->where('status', 1);
						$this->db->where('country_id', $_POST['country_id']);
						$result['states'] = $this->db->get('lkp_state')->result_array();
					} else {
						$result['states'] = array();
					}
				}

				if($_POST['filter_type'] == 'state'){
					if($_POST['state_id'] != 'all'){
						//get states
						$this->db->select('district_id, district_name');
						$this->db->where('status', 1);
						$this->db->where('state_id', $_POST['state_id']);
						$result['districts'] = $this->db->get('lkp_district')->result_array();
					} else {
						$result['districts'] = array();
					}
				}

				if($_POST['filter_type'] == 'district'){
					if($_POST['district_id'] != 'all'){
						//get states
						$this->db->select('centre.centre_id, centre.centre_name');
						$this->db->from('rpt_centre_location as loc');
						$this->db->join('lkp_centre as centre', 'centre.centre_id = loc.centre_id');
						$this->db->where('loc.status', 1);
						$this->db->where('loc.dist', $_POST['district_id']);
						$result['centres'] = $this->db->get()->result_array();
					} else {
						$result['centres'] = array();
					}
				}
			}

			if(isset($_POST['project_id'])){
				echo json_encode($result);
				exit();
			} else {
				$header_result = array('main_menu' => $main_menu);
				$this->load->view('header', $header_result);
				$this->load->view('news/news', $result);
				$this->load->view('footer');
			}
		}
	}
}