<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_model extends CI_Model {
    
	public function __construct() {
		parent::__construct();
		$this->load->library('session');
        $this->load->library('user_agent');
	}

    //Create user access
    public function login($data) {
        // $uuid = $data['uuid'];
        // $ip_address = $this->input->ip_address();
        // $maxAttemptAllowed = 10; $maxSecsToBlock = 60;

        // // Get UUID and IP Address combination in table
        // if($uuid) { $this->db->where('uuid', $uuid); }
        // else { $this->db->where('ip_address', $ip_address); }
        // $attempt = $this->db->order_by('id', 'DESC')->get('tbl_login_attempt');

        // date_default_timezone_set("UTC");
        // //Check if max login attempt reached
        // if($attempt->num_rows() >= $maxAttemptAllowed) {
        //     $attempt = $attempt->row_array();
        //     $currentDatetime = new DateTime();
        //     $lastDateTime = new DateTime($attempt['reg_datetime']);
        //     $interval = $currentDatetime->diff($lastDateTime);
        //     $secondsElapsed = (($interval->i * 60) + $interval->s);

        //     if($secondsElapsed < $maxSecsToBlock) {
        //         $timeleft = gmdate("i:s", ($maxSecsToBlock - $secondsElapsed));
        //         return array(
        //             'msg' => 'Maximum login attempt of '.$maxAttemptAllowed.' is reached. Please wait for another '.$timeleft.' minutes before attempting login.'
        //         );
        //     }
        // }

        // // Insert login attempt details
        // $login_attempt = array(
        //     'ip_address' => $ip_address,
        //     'reg_datetime' => date('Y-m-d H:i:s')
        // );
        // if($uuid) { $login_attempt['uuid'] = $uuid; }
        // $this->db->insert('tbl_login_attempt', $login_attempt);

        switch ($data['logintype']) {
            case 'simple':
                $checkuser = $this->db->select('user_id AS id, salt')->where("(username = '".$data['email']."' OR email_id = '".$data['email']."')")->get('tbl_users');
                if($checkuser->num_rows() === 0) {
                    return false;
                }
            break;

            case 'ldap':
                $username = explode('@', $data['email']);
                $username = $username[0];
                $email = $data['email'];

                //Start LDAP login process
                $ldapport = 636;
                $ldaphostA = "ldaps://AZCGNEROOT2.CGIARAD.ORG";
                $ldaphostB = "ldaps://AZCGCCROOT2.CGIARAD.ORG";

                // Connecting to LDAP
                $ldapconn = ldap_connect($ldaphostB, $ldapport);
                if(!$ldapconn) {
                    return false;
                }

                // configure ldap params
                ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
                ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0);
                ldap_set_option($ldapconn, LDAP_OPT_NETWORK_TIMEOUT, 10);

                // binding to ldap server
                $ldapbind = ldap_bind($ldapconn, $email, $data['password']);
                if(!$ldapbind) {
                    return false;
                }

                $checkuser = $this->db->select('user_id AS id, salt')->where('email_id', $email)->get('tbl_users');
                if($checkuser->num_rows() === 0) {
                    return false;
                }
            break;
        }

        $getData = $checkuser->row_array();
        $password = $data['password'];
        $salt = $getData['salt'];
        $saltedPW =  $password . $salt;
        $hashedPW = hash('sha256', $saltedPW);
        $newData = array(
            'password' => $hashedPW,
            'status' => 1
        );

        switch ($data['logintype']) {
            case 'simple':
                $this->db->select('user_id AS id, first_name, last_name, email_id, role_id');
                $this->db->where("(username = '".$data['email']."' OR email_id = '".$data['email']."')")->where($newData);
            break;

            case 'ldap':
                $this->db->select('user_id AS id, first_name, last_name, email_id, role_id');
                $this->db->where('email_id', $data['email'])->where('status', 1);
            break;
        }
        $query = $this->db->get('tbl_users');

        if($query->num_rows() > 0) {
            $getData = $query->row_array();

            //Clear Session Before Starting a New One
            $data = array('login_id' => '',
                'name' => '', 
                'role' => '', 
                'image' => '', 
                'login_time' => '',
                'main_menu_array' => ''
            );
            $this->session->set_userdata($data);

            $getImage = $this->db->where('user_id', $getData['id'])->where('status', 1)->get('tbl_images')->row_array();
            $login = 'user';
            $image = $getImage['image'];

            $name = empty($getData['first_name']) ? $getData['email_id'] : $getData['first_name'].' '.$getData['last_name'];

            // $this->db->distinct();
            // $this->db->select('GROUP_CONCAT(module_id) as module_ids');
            // $this->db->where('role_id', $getData['role_id'])->where('status', 1);
            // $user_modules = $this->db->group_by('role_id')->get('tbl_role_permissions')->row_array();

            // if($user_modules) {
            //     $user_modules_list = explode(",", $user_modules['module_ids']);
            //     $user_modules_array = array();
            //     foreach ($user_modules_list as $key => $value) {
            //         if(!in_array($value, $user_modules_array)){
            //             array_push($user_modules_array, $value);
            //         }
            //     }

            //     $get_user_modules = $this->db->select('module_id,module_key,module_name')->where_in('module_id', $user_modules_array)->where('status', 1)->order_by('slno')->get('tbl_module')->result_array();

            //     $main_menu_array = array();
            //     foreach ($get_user_modules as $key => $value) {
            //         array_push($main_menu_array, $value['module_key']);
            //     }

            //     if(count($get_user_modules) > 0) {
            //         $this->db->distinct();
            //         $this->db->select('GROUP_CONCAT(permission_id) as permission_ids');
            //         $this->db->where('role_id', $getData['role_id'])->where('module_id', $get_user_modules[0]['module_id'])->where('status', 1);
            //         $user_permission = $this->db->group_by('role_id')->get('tbl_role_permissions')->row_array();

            //         $user_permission_list = explode(",", $user_permission['permission_ids']);
            //         $user_permission_array = array();
            //         foreach ($user_permission_list as $key => $value) {
            //             if(!in_array($value, $user_permission_array)){
            //                 array_push($user_permission_array, $value);
            //             }
            //         }

            //         $get_user_permission = $this->db->select('permission_id, module_key')->where_in('permission_id', $user_permission_array)->where('status', 1)->order_by('slno')->get('tbl_permissions')->result_array();
            //     }
            // } else {
            //     $get_user_modules = array();
            // }
            $get_user_modules = array();

            $return = array(
                'id' => $getData['id'],
                'name' => $name,
                'role' => $getData['role_id'],
                'image' => $image,
                'email' => $getData['email_id'],
                'login' => $login,
                'main_menu_array' => $get_user_modules
            );
            if(count($get_user_modules) > 0) {
                if(count($get_user_permission) > 0){
                    $return['landing_page'] = $get_user_modules[0]['module_key']."/".$get_user_permission[0]['module_key'];
                }else{
                    $return['landing_page'] = $get_user_modules[0]['module_key'];
                }
                //$return['landing_page'] = $get_user_modules[0]['module_key']."/".$get_user_permission[0]['module_key'];
            } else {
                $return['landing_page'] = '';
            }

            // Delete login attempt if successfully logged in
            // if($uuid) {
            //     $this->db->where('uuid', $uuid);
            // } else {
            //     $this->db->where('ip_address', $ip_address);
            // }
            // $this->db->delete('tbl_login_attempt');

            return $return;
        } else {
            return false;
        }
    }

    public function store_account_activity($data, $ci_session) {
        $insert = $this->db->where('id', $ci_session)->update('ci_sessions', $data);
        if($insert) {
            return true;
        } else {
            return false;
        }
    }

    public function match_account_activity() {
        // Prepare where array
        $accActivity = array(
            'id' => $this->input->cookie('ci_sessions', true),
            'ip_address' => $this->input->ip_address(),
            'user_id' => $this->session->userdata('login_id'),
            'browser' => $this->agent->browser(),
            'version' => $this->agent->version(),
            'platform' => $this->agent->platform()
        );

        // Check wheather account is accessed from same details
        $accData = $this->db->where($accActivity)->get('ci_sessions');
        if($accData->num_rows() == 0) return false;
        else return true;
    }
}
