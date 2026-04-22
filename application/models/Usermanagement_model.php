<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usermanagement_model extends CI_Model {
    
	public function __construct() {
		parent::__construct();
		$this->load->library('session');		
	}

    public function get_userslist()
    {
        $this->db->select('users.*, role.role_name');
        $this->db->from('tbl_users as users');
        $this->db->join('tbl_role as role', 'role.role_id = users.role_id');
        $this->db->where('users.status', 1)->where('role.status', 1)->order_by('users.first_name');
        return $get_userslist = $this->db->get()->result_array();
    }

    public function get_rolelist()
    {
        $this->db->where('status', 1);
        return $role_list = $this->db->get('tbl_role')->result_array();
    }

    public function check_emaiid()
    {
        $email = $_POST['email'];
        return $check_emaiid = $this->db->where('email_id', $email)->get('tbl_users')->row_array();
    }

    public function check_username()
    {
        $username = $_POST['username'];
        return $check_username = $this->db->where('username', $username)->get('tbl_users')->row_array();
    }

    public function insert_user()
    {
        date_default_timezone_set("UTC");
        $user_role = $_POST['user_role'];
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $email = $_POST['email'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        $cpassword = $_POST['cpassword'];

        $nsalt = bin2hex(mcrypt_create_iv(32, MCRYPT_DEV_URANDOM));
        $nsaltedPW = $password.$nsalt;
        $nhashedPW = hash('sha256', $nsaltedPW);

        $insert_data = array(
            'username' => $username,
            'email_id' => $email,
            'password' => $nhashedPW,
            'salt' => $nsalt,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'role_id' => $user_role,
            'added_by' => $this->session->userdata('login_id'),
            'added_datetime' => date('Y-m-d H:i:s'),
            'ip_address' => $this->input->ip_address(),
            'status' => 1
        );
        $query = $this->db->insert('tbl_users', $insert_data);
        if($query){
            $user = $this->db->insert_id();
            $insertimage = array(
                'user_id' => $user,
                'image' => 'default.png',
                'original_image' => 'default.png',
                'ip_address' => $this->input->ip_address(),
                'regdate' => date('Y-m-d H:i:s'),
                'status' => 1
            );
            return $insert = $this->db->insert('tbl_images', $insertimage);
        }else{
            return false;
        }
    }

    public function get_capabilitylist()
    {
        $this->db->where('status', 1);
        $role_list = $this->db->get('tbl_role')->result_array();

        $capabilitylist = $this->db->where('status', 1)->get('tbl_module')->result_array(); 

        foreach ($capabilitylist as $key => $module) {
            $permissions = $this->db->where('module_id', $module['module_id'])->where('status', 1)->order_by('slno')->get('tbl_permissions')->result_array();

            foreach ($permissions as $pkey => $permission) {
                foreach ($role_list as $rkey => $role) {
                    $check_permission = $this->db->where('role_id', $role['role_id'])->where('module_id', $module['module_id'])->where('permission_id', $permission['permission_id'])->where('status', 1)->get('tbl_role_permissions')->num_rows();
                    if($check_permission == 0){
                        $permissions[$pkey][$role['role_name']] = 0;
                    }else{
                        $permissions[$pkey][$role['role_name']] = 1;
                    }
                }
            }
            $capabilitylist[$key]['permissions'] = $permissions;
        }
        return $capabilitylist;
    }

    public function insert_role()
    {
        date_default_timezone_set("UTC");
        $copy_role = $_POST['copy_role'];
        $role_name = $_POST['role_name'];
        $role_description = $_POST['role_description'];

        $insert_data = array(
            'role_name' => $role_name,
            'role_description' => $role_description,
            'added_by' => $this->session->userdata('login_id'),
            'added_date' => date('Y-m-d H:i:s'),
            'ip_address' => $this->input->ip_address(),
            'status' => 1
        );

        $query = $this->db->insert('tbl_role', $insert_data);

        if($query){
            $formlast_insertid = $this->db->insert_id();

            $get_permission_byrole = $this->db->where('role_id', $copy_role)->where('status', 1)->get('tbl_role_permissions')->result_array();

            foreach ($get_permission_byrole as $key => $value) {
                $permission_data = array(
                    'role_id' => $formlast_insertid,
                    'module_id' => $value['module_id'],
                    'permission_id' => $value['permission_id'],
                    'added_by' => $this->session->userdata('login_id'),
                    'added_date' => date('Y-m-d H:i:s'),
                    'ip_address' => $this->input->ip_address(),
                    'status' => 1
                );

                $permissions_query = $this->db->insert('tbl_role_permissions', $permission_data);

                if(!$permissions_query){
                    return false;
                }
            }                
        }else{
            return false;
        }

        return true;
    }

    public function update_permissions()
    {
        date_default_timezone_set("UTC");
        $assigingstatus = $_POST['assigingstatus'];
        $roleid = $_POST['roleid'];
        $moduleid = $_POST['moduleid'];
        $permissionid = $_POST['permissionid'];

        if($assigingstatus == 0){
            $update_data = array(
                'status' => 0
            );

            $this->db->where('role_id', $roleid)->where('module_id', $moduleid)->where('permission_id', $permissionid);
            $updatequery = $this->db->update('tbl_role_permissions', $update_data);

            if(!$updatequery){
                return false;
            }else{
                return true;
            }
        }else{
            $insert_data = array(
                'role_id' => $roleid,
                'module_id' => $moduleid,
                'permission_id' => $permissionid,
                'added_by' => $this->session->userdata('login_id'),
                'added_date' => date('Y-m-d H:i:s'),
                'ip_address' => $this->input->ip_address(),
                'status' => 1
            );

            $insert_query = $this->db->insert('tbl_role_permissions', $insert_data);

            if(!$insert_query){
                return false;
            }else{
                return true;
            }
        }
    }
}   
