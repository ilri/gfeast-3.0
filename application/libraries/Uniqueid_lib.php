<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source unique id generator that returns different
 * unique ids using random_bytes && Shuffle technique
 *
 * @package     CodeIgniter
 * @author      Abhinash & Abhinit
 * @copyright   Copyright (c) 2018,.
 * @license     
 * @link        
 * @since       Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * @package     CodeIgniter
 * @subpackage  Libraries
 * @category    Unique ID Generator
 * @author      Abhinash & Abhinit
 * @link        
 */

class Uniqueid_lib {
	var $CI;

	public function __construct($params = array())
	{
		$this->CI =& get_instance();

		$this->CI->load->helper('url');
		$this->CI->config->item('base_url');
		$this->CI->load->library('session');
	}
	
	//GUIDV4 ids using random_bytes
	public function guidv4()
	{
		$data = openssl_random_pseudo_bytes(16);
		assert(strlen($data) == 16);

		$data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
		$data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10

		return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
	}

	//using shuffle technique
	public function shuffle()
	{
		$alpha   = str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ');
		$numeric = str_shuffle('0123456789');
		
		$code = substr($alpha, 0, 4) . substr($numeric, 0, 4);
		$code = str_shuffle($code);

		return $code;
	}

	//using shuffle technique
	public function shuffle_10()
	{
		$alpha   = str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ');
		$numeric = str_shuffle('0123456789');
		
		$code = substr($alpha, 0, 5) . substr($numeric, 0, 5);
		$code = str_shuffle($code);

		return $code;
	}
}