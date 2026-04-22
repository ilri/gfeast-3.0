<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dynamicmenu_model extends CI_Model {
    
	public function __construct() {
		parent::__construct();
		$this->load->library('session');		
	}

    public function menu_details()
    {
        $baseurl = base_url();
        $permission_list = '';
        
        $allowed_access = array();
        $method = $this->router->fetch_method();
        $controller = $this->router->fetch_class();

        $this->db->distinct();
        $this->db->select('GROUP_CONCAT(module_id) as module_ids');
        $this->db->where('role_id', $this->session->userdata('role'))->where('status', 1);
        $user_modules = $this->db->group_by('role_id')->get('tbl_role_permissions')->row_array();

        if(is_null($user_modules)) {
            redirect($baseurl);
        }
        
        $user_modules_list = explode(",", $user_modules['module_ids']);
        $user_modules_array = array();
        foreach ($user_modules_list as $key => $value) {
            if(!in_array($value, $user_modules_array)){
                array_push($user_modules_array, $value);
            }
        }

        $get_user_modules = $this->db->select('module_id,module_key,module_name')->where_in('module_id', $user_modules_array)->where('status', 1)->order_by('slno')->get('tbl_module')->result_array();

        foreach ($get_user_modules as $mkey => $module) {
            $this->db->distinct();
            $this->db->select('GROUP_CONCAT(permission_id) as permission_ids');
            $this->db->where('role_id', $this->session->userdata('role'))->where('module_id', $module['module_id'])->where('status', 1);
            $user_permission = $this->db->group_by('role_id')->get('tbl_role_permissions')->row_array();

            $user_permission_list = explode(",", $user_permission['permission_ids']);
            $user_permission_array = array();
            foreach ($user_permission_list as $key => $value) {
                if(!in_array($value, $user_permission_array)){
                    array_push($user_permission_array, $value);
                }
            }

            $get_user_modules[$mkey]['permission_list'] = $this->db->select('permission_id, module_key, name')->where_in('permission_id', $user_permission_array)->where('status', 1)->order_by('slno')->get('tbl_permissions')->result_array();

            $allowed_access[$module['module_key']] = array();
            foreach ($get_user_modules[$mkey]['permission_list'] as $pkey => $permission) {
                if(!in_array($permission['module_key'], $allowed_access[$module['module_key']])) {
                    array_push($allowed_access[$module['module_key']], $permission['module_key']);
                }
            }

            /*if($this->uri->segment(1) == $module['module_key']){
                $permission_list = $get_user_permission;
            }

            $get_user_modules[$mkey]['permission_list'] = $get_user_permission[0]['module_key'];*/
        }
        
        // $moduleMatch = false;
        // $permissionMatch = false;
        // if(strtolower($controller) !== 'login'
        // && strtolower($controller) !== 'reports'
        // && strtolower($controller) !== 'password'
        // && strtolower($controller) !== 'locationsetting') {
        //     foreach ($allowed_access as $module => $permissions) {
        //         // Module Allowed or Not
        //         if(strtolower($controller) === $module) $moduleMatch = true;
        //         // Permission Allowed or Not
        //         if(strtolower($controller) === $module) {
        //             if(in_array(strtolower($method), $permissions)) $permissionMatch = true;
        //         }
        //     }
        // } else {
        //     $moduleMatch = true;
        //     $permissionMatch = true;
        // }
        // if(!$moduleMatch || !$permissionMatch) redirect($baseurl);

        /*return $result = array('permission_list' => $permission_list, 'get_user_modules' => $get_user_modules);*/

        return $result = array('get_user_modules' => $get_user_modules);
    }

    public function get_landingpage()
    {
        $this->db->distinct();
        $this->db->select('GROUP_CONCAT(module_id) as module_ids');
        $this->db->where('role_id', $this->session->userdata('role'))->where('status', 1);
        $user_modules = $this->db->group_by('role_id')->get('tbl_role_permissions')->row_array();

        if($user_modules) {
            $user_modules_list = explode(",", $user_modules['module_ids']);
            $user_modules_array = array();
            foreach ($user_modules_list as $key => $value) {
                if(!in_array($value, $user_modules_array)){
                    array_push($user_modules_array, $value);
                }
            }

            $get_user_modules = $this->db->select('module_id,module_key,module_name')->where_in('module_id', $user_modules_array)->where('status', 1)->order_by('slno')->get('tbl_module')->result_array();

            $main_menu_array = array();
            foreach ($get_user_modules as $key => $value) {
                array_push($main_menu_array, $value['module_key']);
            }

            if(count($get_user_modules) > 0) {
                $this->db->distinct();
                $this->db->select('GROUP_CONCAT(permission_id) as permission_ids');
                $this->db->where('role_id', $this->session->userdata('role'))->where('module_id', $get_user_modules[0]['module_id'])->where('status', 1);
                $user_permission = $this->db->group_by('role_id')->get('tbl_role_permissions')->row_array();

                $user_permission_list = explode(",", $user_permission['permission_ids']);
                $user_permission_array = array();
                foreach ($user_permission_list as $key => $value) {
                    if(!in_array($value, $user_permission_array)){
                        array_push($user_permission_array, $value);
                    }
                }

                $get_user_permission = $this->db->select('permission_id, module_key')->where_in('module_id', $user_permission_array)->where('status', 1)->order_by('slno')->get('tbl_permissions')->result_array();
            }
        } else {
            $get_user_modules = array();
        }

        if(count($get_user_modules) > 0) {
            $landing_url = $get_user_modules[0]['module_key']."/".$get_user_permission[0]['module_key'];
        } else {
            $landing_url = 'nopermission';
        }
        return $landing_url;
    }
}
