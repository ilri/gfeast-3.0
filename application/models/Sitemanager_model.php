<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sitemanager_model extends CI_Model {

	public function get_counties($data)
	{
		$this->db->select('*');
		$this->db->from('lkp_county');
		if(!empty($data['county_id'])){
			$this->db->where('county_id',$data['county_id']);
		}
		$this->db->where('status',1);
		$this->db->limit(500);
		return $this->db->get()->result();
	}

				
				//get yesnos(by id)
	public function get_yesnos($data)
	{
		$this->db->select('*');
		$this->db->from('lkp_yesno');
		if(!empty($data['id'])){
			$this->db->where('id',$data['id']);
		}
		$this->db->limit(500);
		return $this->db->get()->result();
	}
			
			//get trainingpartnerss (by id)
	public function get_trainingpartnerss($data)
	{
		$this->db->select('*');
		$this->db->from('lkp_trainingpartners');
		if(!empty($data['id'])){
			$this->db->where('id',$data['id']);
		}
		$this->db->limit(500);
		return $this->db->get()->result();
	}
			
			//get technologytypes(by id)
	public function get_technologytypes($data)
	{
		$this->db->select('*');
		$this->db->from('lkp_technologytype');
		if(!empty($data['technologytype_id'])){
			$this->db->where('technologytype_id',$data['technologytype_id']);
		}
		$this->db->limit(500);
		return $this->db->get()->result();
	}
			
			//get technologypractice(by id)
	public function get_technologypractices($data)
	{
		$this->db->select('*');
		$this->db->from('lkp_technologypractice');
		if(!empty($data['id'])){
			$this->db->where('id',$data['id']);
		}
		$this->db->limit(500);
		return $this->db->get()->result();
	}

	public function get_subcounty($data)
	{
		$this->db->select('s.*,c.name');
		$this->db->from('lkp_sub_county s');
		$this->db->join('lkp_county c','c.county_id=s.county_id');
		if(!empty($data['county_id'])){
			$this->db->where('s.county_id',$data['county_id']);
		}
		if(!empty($data['subcounty_id']))
		{
			$this->db->where('sub_county_id',$data['subcounty_id']);
		}
		$this->db->where('s.status',1);
		$this->db->limit(500);
		return $this->db->get()->result();
	}
	public function get_ward($data)
	{
		$this->db->select('w.*,c.name,s.sub_county_name');
		$this->db->from('lkp_ward w');
		$this->db->join('lkp_county c','c.county_id=w.county_id');
		$this->db->join('lkp_sub_county s','s.sub_county_id=w.sub_county_id');
		if(!empty($data['sub_county_id'])){
			$this->db->where('s.sub_county_id',$data['sub_county_id']);
		}
		if(!empty($data['ward_id']))
		{
			$this->db->where('ward_id',$data['ward_id']);
		}
		$this->db->where('w.status',1);
		$this->db->limit(500);
		return $this->db->get()->result();
	}
	public function save_county($data)
	{
		if(!empty($data['county_id']))
		{
			return $this->db->where('county_id',$data['county_id'])->update('lkp_county',$data);
		}
		else{
			return $this->db->insert('lkp_county',$data);
		}

	}
	public function save_subcounty($data)
	{
		if(!empty($data['sub_county_id'])){
			$this->db->where('sub_county_id',$data['sub_county_id']);
			return $this->db->update('lkp_sub_county',$data);
		}
		else
		{
			return $this->db->insert('lkp_sub_county',$data);
		}

	}
	
	//save save_vc_actor_type
	public function save_vc_actor_type($data)
	{
		if(!empty($data['vc_actor_id']))
		{
			 $this->db->where('vc_actor_id',$data['vc_actor_id']);
			 return $this->db->update('lkp_vc_actor_type',$data);
		}
		else{
			return $this->db->insert('lkp_vc_actor_type',$data);
		}

	}

	public function getsubcounty_by_county($data)
	{
		$this->db->select('*');
		$this->db->from('lkp_sub_county');
		$this->db->where('county_id',$data['county_id']);
		return $this->db->get()->result();
	}
	public function save_ward($data)
	{
		if(!empty($data['ward_id']))
		{
			return $this->db->where('ward_id',$data['ward_id'])->update('lkp_ward',$data);
		}
		else
		{
			return $this->db->insert('lkp_ward',$data);
		}

	}
	public function county_delete($data)
	{
		if($data['county_status']==1)
		{
			$cstatus=0;
		}
		if($data['county_status']==0)
		{
			$cstatus=1;
		}
		$res=$this->db->where('county_id',$data['county_id'])->set('status',$cstatus)->update('lkp_county');
		if($res)
		{
			return $cstatus;
		}

	}

			
			//yesno ajax
	public function yesno_delete($data)
	{
		if($data['yesno_status']==1)
		{
			$cstatus=0;
		}
		if($data['yesno_status']==0)
		{
			$cstatus=1;
		}
		$res=$this->db->where('id',$data['id'])->set('status',$cstatus)->update('lkp_yesno');
		if($res)
		{
			return $cstatus;
		}

	}
		
		//Market ajax
	public function market_delete($data)
	{
		if($data['market_status']==1)
		{
			$cstatus=0;
		}
		if($data['market_status']==0)
		{
			$cstatus=1;
		}
		$res=$this->db->where('id',$data['id'])->set('status',$cstatus)->update('lkp_market');
		if($res)
		{
			return $cstatus;
		}

	}
		
		//get market(by id)
	public function get_markets($data)
	{
		$this->db->select('*');
		$this->db->from('lkp_market');
		if(!empty($data['id'])){
			$this->db->where('id',$data['id']);
		}
		$this->db->limit(500);
		return $this->db->get()->result();
	}
		
		//save market
	public function save_market($data)
	{
		if(!empty($data['id']))
		{
			 $this->db->where('id',$data['id']);
			return $this->db->update('lkp_market',$data);

		}
		else{
			return $this->db->insert('lkp_market',$data);
		}

	}
		
		//respondentritn ajax
	public function respondentritn_delete($data)
	{
		if($data['respondentritn_status']==1)
		{
			$cstatus=0;
		}
		if($data['respondentritn_status']==0)
		{
			$cstatus=1;
		}
		$res=$this->db->where('id',$data['id'])->set('status',$cstatus)->update('lkp_respondentritn');
		if($res)
		{
			return $cstatus;
		}

	}
		
		//school ajax
	public function school_delete($data)
	{
		if($data['school_status']==1)
		{
			$cstatus=0;
		}
		if($data['school_status']==0)
		{
			$cstatus=1;
		}
		$res=$this->db->where('school_id',$data['school_id'])->set('choice_status',$cstatus)->update('lkp_school');
		if($res)
		{
			return $cstatus;
		}

	}
		
		//technologypractice ajax
	public function technologypractice_delete($data)
	{
		if($data['technologypractice_status']==1)
		{
			$cstatus=0;
		}
		if($data['technologypractice_status']==0)
		{
			$cstatus=1;
		}
		$res=$this->db->where('id',$data['id'])->set('status',$cstatus)->update('lkp_technologypractice');
		if($res)
		{
			return $cstatus;
		}

	}
		
		//technologytype ajax
	public function technologytype_delete($data)
	{
		if($data['status']==1)
		{
			$cstatus=0;
		}
		if($data['status']==0)
		{
			$cstatus=1;
		}
		$res=$this->db->where('technologytype_id',$data['technologytype_id'])->set('status',$cstatus)->update('lkp_technologytype');
		if($res)
		{
			return $cstatus;
		}

	}

		
		//trainingpartners ajax
	public function trainingpartners_delete($data)
	{
		if($data['status']==1)
		{
			$cstatus=0;
		}
		if($data['status']==0)
		{
			$cstatus=1;
		}
		$res=$this->db->where('id',$data['id'])->set('status',$cstatus)->update('lkp_trainingpartners');
		if($res)
		{
			return $cstatus;
		}

	}

	public function subcounty_delete($data)
	{
		if($data['subcounty_status']==1)
		{
			$cstatus=0;
		}
		if($data['subcounty_status']==0)
		{
			$cstatus=1;
		}
		$res=$this->db->where('sub_county_id',$data['subcounty_id'])->set('status',$cstatus)->update('lkp_sub_county');
		if($res)
		{
			return $cstatus;
		}

	}
	public function ward_delete($data)
	{
		if($data['status']==1)
		{
			$cstatus=0;
		}
		if($data['status']==0)
		{
			$cstatus=1;
		}
		$res=$this->db->where('ward_id',$data['ward_id'])->set('status',$cstatus)->update('lkp_ward');
		if($res)
		{
			return $cstatus;
		}

	}
	public function get_debt_type()
	{
		$this->db->select('*');
		$this->db->from('lkp_debt_type');
		return $this->db->get()->result();
	}
	public function get_dtcfarmer_type()
	{
		$this->db->select('t.*,u.first_name,u.last_name');
		$this->db->from('lkp_dtcfarmertype t');
		$this->db->join('tbl_users u','u.user_id=t.added_by');
		return $this->db->get()->result();
	}
	public function get_education()
	{
		$this->db->select('e.*,u.first_name,u.last_name');
		$this->db->from('lkp_education e');
		$this->db->join('tbl_users u','u.user_id=e.added_by');
		return $this->db->get()->result();
	}
	
					
					//eventtype
	public function get_eventtype()
	{
		$this->db->select('event.*,type.name as typename,specific.name specificname');
		$this->db->from('lkp_eventtype event');
		$this->db->join('lkp_trainingtype type','type.id=event.trainingtype_id');
		$this->db->join('lkp_trainingspecifics specific','specific.id=event.trainingspecifics_id');
		return $this->db->get()->result();
	}
				
				//financingaccessed_type
	public function get_financingaccessed_type()
	{
		$this->db->select('*');
		$this->db->from('lkp_financingaccessed_type');
		return $this->db->get()->result();
	}
				
				//Gender
	public function get_gender()
	{
		$this->db->select('*');
		$this->db->from('lkp_gender');
		return $this->db->get()->result();
	}
				
				//Market
	public function get_market()
	{
		$this->db->select('*');
		$this->db->from('lkp_market');
		return $this->db->get()->result();
	}
				
				//respondentritn
	public function get_respondentritn()
	{
		$this->db->select('*');
		$this->db->from('lkp_respondentritn');
		return $this->db->get()->result();
	}
				
				//school
	public function get_school()
	{
		$this->db->select('school.*,u.first_name,u.last_name');
		$this->db->from('lkp_school school');
		$this->db->join('tbl_users u','u.user_id=school.added_by');
		return $this->db->get()->result();
	}
				
				//technologypractice
	public function get_technologypractice()
	{
		$this->db->select('*');
		$this->db->from('lkp_technologypractice');
		return $this->db->get()->result();
	}
				
				//technologytype
	public function get_technologytype()
	{
		$this->db->select('type.*,u.first_name,u.last_name');
		$this->db->from('lkp_technologytype type');
		$this->db->join('tbl_users u','u.user_id=type.added_by');
		return $this->db->get()->result();
	}
				
				//trainingpartners
	public function get_trainingpartners()
	{
		$this->db->select('*');
		$this->db->from('lkp_trainingpartners');
		return $this->db->get()->result();
	}
				
				//yesno
	public function get_yesno()
	{
		$this->db->select('*');
		$this->db->from('lkp_yesno');
		return $this->db->get()->result();
	}

	public function get_subeventtype($data)
	{
		$this->db->select('*');
		$this->db->from('lkp_eventtype');
		if(!empty($data['eventtype_id'])){
			$this->db->where('id',$data['eventtype_id']);
		}
		$this->db->limit(500);
		return $this->db->get()->result();
	}
				
				//save yesno
	public function save_yesno($data)
	{
		if(!empty($data['id']))
		{
			$this->db->where('id',$data['id']);
			return $this->db->update('lkp_yesno',$data);

		}
		else{
			return $this->db->insert('lkp_yesno',$data);
		}

	}
				
				//save technologytype
	public function save_technologytype($data)
	{
		if(!empty($data['technologytype_id']))
		{
			 $this->db->where('technologytype_id',$data['technologytype_id']);
			 return $this->db->update('lkp_technologytype',$data);

		}
		else{
			return $this->db->insert('lkp_technologytype',$data);
		}

	}
				
				//save _technologypractice
	public function save_technologypractice($data)
	{
		if(!empty($data['id']))
		{
			 $this->db->where('id',$data['id']);
			 return $this->db->update('lkp_technologypractice',$data);

		}
		else{
			return $this->db->insert('lkp_technologypractice',$data);
		}

	}
				
	

	public function get_training_specifics($data)
	{
		$this->db->select('spec.*,type.name type_name');
		$this->db->from(' lkp_trainingspecifics spec');
		if(!empty($data['specific_id']))
		{
			$this->db->where('spec.id',$data['specific_id']);
		}
		$this->db->join('lkp_trainingtype type','type.id=spec.trainingtype_id');
		return $this->db->get()->result();
	}
	public function get_training_type($data)
	{
		$this->db->select('*');
		$this->db->from(' lkp_trainingtype');
		if(!empty($data['trainingtype_id']))
		{
			$this->db->where('id',$data['trainingtype_id']);
		}
		return $this->db->get()->result();
	}
	public function get_valuechain($data)
	{
		$this->db->select('*');
		$this->db->from(' lkp_value_chain');
		if(!empty($data['valuechain_id']))
		{
			$this->db->where('value_chain_id',$data['valuechain_id']);
		}
		return $this->db->get()->result();
	}
	public function insert_valuechain($data)
	{
		if(!empty($data['value_chain_id']))
		{
			return $this->db->where('value_chain_id',$data['value_chain_id'])->update('lkp_value_chain',$data);
		}
		else
		{
			return $this->db->insert('lkp_value_chain',$data);
		}

	}
	public function delete_valuechain($data)
	{
		if($data['status']==0)
		{
			$status=1;
		}
		else
		{
			$status=0;
		}
		$res=$this->db->where('value_chain_id',$data['value_chain_id'])->set('status',$status)->update('lkp_value_chain');
		if($res)
		{
			return $status;
		}
	}
	public function add_trainingtype($data)
	{
		if(!empty($data['id']))
		{
			return $this->db->where('id',$data['id'])->update('lkp_trainingtype',$data);
		}
		else{
			return $this->db->insert('lkp_trainingtype',$data);
		}

	}
	public function insert_training_specific($data)
	{
		if(!empty($data['id']))
		{
			$this->db->where('id',$data['id']);
			return $this->db->update('lkp_trainingspecifics',$data);
		}
		else{
			return $this->db->insert('lkp_trainingspecifics',$data);
		}

	}
	public function delete_specific($data)
	{
		if($data['status']==0)
		{
			$status=1;
		}
		else
		{
			$status=0;
		}
		$res=$this->db->where('id',$data['specific_id'])->set('status',$status)->update('lkp_trainingspecifics');
		if($res)
		{
			return $status;
		}
	}
	public function get_specificsby_type($data){

		$this->db->select('*');
		$this->db->from('lkp_trainingspecifics');
		$this->db->where('trainingtype_id',$data['type_id']);
		return $this->db->get()->result();

	}
	public function add_eventtype($data){
		if(!empty($data['type_id']))
		{
			$this->db->where('id',$data['type_id']);
			return $this->db->update('lkp_eventtype',$data);
		}
		else{
			return $this->db->insert('lkp_eventtype',$data);
		}

	}
	
	//save _technologypractice
	public function save_school($data)
	{
		if(!empty($data['school_id']))
		{
			 $this->db->where('school_id',$data['school_id']);
			return $this->db->update('lkp_school',$data);

		}
		else{
			return $this->db->insert('lkp_school',$data);
		}

	}
	
	//get schools(by id)
	public function get_schools($data)
	{
		$this->db->select('*');
		$this->db->from('lkp_school');
		if(!empty($data['school_id'])){
			$this->db->where('school_id',$data['school_id']);
		}
		$this->db->limit(500);
		return $this->db->get()->result();
	}
	
	//get respondentritns(by id)
	public function get_respondentritns($data)
	{
		$this->db->select('*');
		$this->db->from('lkp_respondentritn');
		if(!empty($data['id'])){
			$this->db->where('id',$data['id']);
		}
		$this->db->limit(500);
		return $this->db->get()->result();
	}
	
	//save respondentritn
	public function save_respondentritn($data)
	{
		if(!empty($data['id']))
		{
			 $this->db->where('id',$data['id']);
			return $this->db->update('lkp_respondentritn',$data);

		}
		else{
			return $this->db->insert('lkp_respondentritn',$data);
		}

	}
	
	//get gender(by id)
	public function get_genders($data)
	{
		$this->db->select('*');
		$this->db->from('lkp_gender');
		if(!empty($data['id'])){
			$this->db->where('id',$data['id']);
		}
		$this->db->limit(500);
		return $this->db->get()->result();
	}
	
	//save gender
	public function save_gender($data)
	{
		if(!empty($data['id']))
		{
			 $this->db->where('id',$data['id']);
			return $this->db->update('lkp_gender',$data);

		}
		else{
			return $this->db->insert('lkp_gender',$data);
		}

	}
	
	//gender ajax
	public function gender_delete($data)
	{
		if($data['gender_status']==1)
		{
			$cstatus=0;
		}
		if($data['gender_status']==0)
		{
			$cstatus=1;
		}
		$res=$this->db->where('id',$data['id'])->set('status',$cstatus)->update('lkp_gender');
		if($res)
		{
			return $cstatus;
		}

	}
	
	//get financingaccessed_type(by id)
	public function get_financingaccessed_types($data)
	{
		$this->db->select('*');
		$this->db->from('lkp_financingaccessed_type');
		if(!empty($data['id'])){
			$this->db->where('id',$data['id']);
		}
		$this->db->limit(500);
		return $this->db->get()->result();
	}
	
	//save financingaccessed_type
	public function save_financingaccessed_type($data)
	{
		if(!empty($data['id']))
		{
			 $this->db->where('id',$data['id']);
			return $this->db->update('lkp_financingaccessed_type',$data);

		}
		else{
			return $this->db->insert('lkp_financingaccessed_type',$data);
		}

	}
	
	//financingaccessed_type ajax
	public function financingaccessed_type_delete($data)
	{
		if($data['financingaccessed_type_status']==1)
		{
			$cstatus=0;
		}
		if($data['financingaccessed_type_status']==0)
		{
			$cstatus=1;
		}
		$res=$this->db->where('id',$data['id'])->set('status',$cstatus)->update('lkp_financingaccessed_type');
		if($res)
		{
			return $cstatus;
		}

	}
	
	//get eventtype(by id)
	public function get_eventtypes($data)
	{	
		$datas=array();
		$this->db->select('*')->from('lkp_eventtype');
		if(!empty($data['eventtype_id'])){
			$this->db->where('id',$data['eventtype_id']);
		}
		$this->db->limit(500);
		$res =  $this->db->get()->result_array();
			foreach ($res as $key => $value) {
			$eid = $value['id'];
			$ename = $value['name'];
			$tid = $value['trainingtype_id'];
			$tsid = $value['trainingspecifics_id'];
				$this->db->select('*')->from('lkp_trainingtype');
				$this->db->where('id',$tid);
				$this->db->limit(500);
				$res2 = $this->db->get()->result_array();
					foreach ($res2 as $key => $value) {
						$tname = $value['name'];
					}
				$this->db->select('*')->from('lkp_trainingspecifics');
				$this->db->where('trainingtype_id',$tid);
				$this->db->limit(500);
				$res3 = $this->db->get()->result_array();
					foreach ($res3 as $key => $value) {
						$tsname = $value['name'];
					}
					$datas =array('id'=> $eid,'ename'=> $ename, 'tid'=> $tid, 'tname'=>$tname, 'tsid'=> $tsid, 'tsname'=>$tsname);
		
				return $datas;

			}
		// $this->db->limit(500);
		// var_dump($res);die();
	}
	
	//save eventtype
	public function save_eventtype($data)
	{
		if(!empty($data['id']))
		{ 
			 $this->db->where('id',$data['id']);
			 $this->db->where('trainingtype_id',$data['trainingtype_id']);
			 $this->db->where('trainingspecifics_id',$data['trainingspecifics_id']);
			return $this->db->update('lkp_eventtype',$data);
		}
		else{
			return $this->db->insert('lkp_eventtype',$data);
		}

	}
	
	//eventtype ajax
	public function eventtype_delete($data)
	{
		if($data['eventtype_status']==1)
		{
			$cstatus=0;
		}
		if($data['eventtype_status']==0)
		{
			$cstatus=1;
		}
		$res=$this->db->where('id',$data['id'])->set('status',$cstatus)->update('lkp_eventtype');
		if($res)
		{
			return $cstatus;
		}

	}
	
	//vc_actor_type
	public function get_vc_actor_type()
	{
		$this->db->select('type.*,u.first_name,u.last_name');
		$this->db->from('lkp_vc_actor_type type');
		$this->db->join('tbl_users u','u.user_id=type.added_by');
		return $this->db->get()->result();
	}
	
	//get vc_actor_type(by id)
	public function get_vc_actor_types($data)
	{
		$this->db->select('*');
		$this->db->from('lkp_vc_actor_type');
		if(!empty($data['vc_actor_id'])){
			$this->db->where('vc_actor_id',$data['vc_actor_id']);
		}
		$this->db->limit(500);
		return $this->db->get()->result();
	}
	
	
	//vc_actor_type ajax
	public function vc_actor_type_delete($data)
	{
		if($data['status']==1)
		{
			$cstatus=0;
		}
		if($data['status']==0)
		{
			$cstatus=1;
		}
		$res=$this->db->where('vc_actor_id',$data['vc_actor_id'])->set('status',$cstatus)->update('lkp_vc_actor_type');
		if($res)
		{
			return $cstatus;
		}

	}

	
	//get education(by id)
	public function get_educations($data)
	{
		$this->db->select('*');
		$this->db->from('lkp_education');
		if(!empty($data['education_id'])){
			$this->db->where('education_id',$data['education_id']);
		}
		$this->db->limit(500);
		return $this->db->get()->result();
	}
	
	//save education
	public function save_education($data)
	{
		if(!empty($data['education_id']))
		{
			return $this->db->where('education_id',$data['education_id'])->update('lkp_education',$data);

		}
		else{
			return $this->db->insert('lkp_education',$data);
		}

	}
	
	//education ajax
	public function education_delete($data)
	{
		if($data['education_status']==1)
		{
			$cstatus=0;
		}
		if($data['education_status']==0)
		{
			$cstatus=1;
		}
		$res=$this->db->where('education_id',$data['education_id'])->set('education_status',$cstatus)->update('lkp_education');
		if($res)
		{
			return $cstatus;
		}

	}
	//save trainingpartners
		public function save_trainingpartners($data)
	{
		if(!empty($data['id']))
		{
			 $this->db->where('id',$data['id']);
			return $this->db->update('lkp_trainingpartners',$data);
		}
		else{
			return $this->db->insert('lkp_trainingpartners',$data);
		}

	}

	//debttype ajax
	public function debttype_delete($data)
	{
		if($data['status']==1)
		{
			$cstatus=0;
		}
		if($data['status']==0)
		{
			$cstatus=1;
		}
		$res=$this->db->where('id',$data['id'])->set('status',$cstatus)->update('lkp_debt_type');
		if($res)
		{
			return $cstatus;
		}

	}
	
	//save debttype
	public function save_debttype($data)
	{
		if(!empty($data['id']))
		{
			return $this->db->where('id',$data['id'])->update('lkp_debt_type',$data);

		}
		else{
			return $this->db->insert('lkp_debt_type',$data);
		}

	}


	//get debttype(by id)
	public function get_debttypes($data)
	{
		$this->db->select('*');
		$this->db->from('lkp_debt_type');
		if(!empty($data['id'])){
			$this->db->where('id',$data['id']);
		}
		$this->db->limit(500);
		return $this->db->get()->result();
	}


	//dtcfarmer ajax
	public function dtcfarmer_delete($data)
	{
		if($data['status']==1)
		{
			$cstatus=0;
		}
		if($data['status']==0)
		{
			$cstatus=1;
		}
		$res=$this->db->where('id',$data['id'])->set('status',$cstatus)->update('lkp_dtcfarmertype');
		if($res)
		{
			return $cstatus;
		}

	}
	
	//save dtcfarmer
	public function save_dtcfarmer($data)
	{
		if(!empty($data['id']))
		{
			return $this->db->where('id',$data['id'])->update('lkp_dtcfarmertype',$data);

		}
		else{
			return $this->db->insert('lkp_dtcfarmertype',$data);
		}

	}


	//get dtcfarmer(by id)
	public function get_dtcfarmers($data)
	{
		$this->db->select('*');
		$this->db->from('lkp_dtcfarmertype');
		if(!empty($data['id'])){
			$this->db->where('id',$data['id']);
		}
		$this->db->limit(500);
		return $this->db->get()->result();
	}


}
?>