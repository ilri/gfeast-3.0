<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Compress_model extends CI_Model {
	public function __construct() {
		parent::__construct();		
	}

	//Image Compression
	//New Upload using Compression
	/*public function compress()
	{
		$baseurl = base_url();

		date_default_timezone_set("UTC");

		if(isset($_FILES['img']) && $_FILES['img']['size'][0] > 0) {
		$timestamp = new DateTime();
		$timestamp = $timestamp->format('U');
		$name = 'activity_' .$timestamp. '_' .$_FILES['img']['name'][0];
		$url = 'imgupload/'.$name;

		$filename = $this->compress_image($_FILES["img"]["tmp_name"][0], $url, 80);
		}

		$this->load->view('compression-upload');
	}*/

	public function compress_image_file($source_url, $destination_url, $image_size)
	{
		$info = getimagesize($source_url);
		if ($info['mime'] == 'image/jpeg') $image = imagecreatefromjpeg($source_url);
		elseif ($info['mime'] == 'image/gif') $image = imagecreatefromgif($source_url);
		elseif ($info['mime'] == 'image/png') $image = imagecreatefrompng($source_url);

		$roughsize = (((int)$image_size) / 1024.0);
		$size =  (int)round($roughsize,2);

		/*if($size <= 300) {
			imagejpeg($image, $destination_url, 99);
		} else if($size > 300 && $size <= 1024) {
			imagejpeg($image, $destination_url, 90);
		} else if($size > 1024 && $size <= 3072) {
			imagejpeg($image, $destination_url, 85);
		} else {
			imagejpeg($image, $destination_url, 80);
		}*/
		imagejpeg($image, $destination_url, 50);
		return $destination_url;
	}

	public function compress_image_base64($source_url, $destination_url, $quality) {
		$bytes = strlen($source_url);
		$roughsize = (((int)$bytes) / 1024.0);
		$size =  (int)round($roughsize,2);

		$image = imagecreatefromstring($source_url);
		/*if($size <= 300) {
			imagejpeg($image, $destination_url, 95);
		} else if($size > 300 && $size <= 1024) {
			imagejpeg($image, $destination_url, 90);
		} else if($size > 1024 && $size <= 3072) {
			imagejpeg($image, $destination_url, 85);
		} else {
			imagejpeg($image, $destination_url, 80);
		}*/
		imagejpeg($image, $destination_url, 50);
		return $destination_url;
	}

	public function compress_image_mobile($source_url, $destination_url, $quality) {
		$bytes = strlen($source_url);
		$roughsize = (((int)$bytes) / 1024.0);
		$size =  (int)round($roughsize,2);

		$image = imagecreatefromstring($source_url);
		/*if($size <= 300) {
			imagejpeg($image, $destination_url, 99);
		} else if($size > 300 && $size <= 3072) {
			imagejpeg($image, $destination_url, 98);
		} else if($size > 3072 && $size <= 4096) {
			imagejpeg($image, $destination_url, 97);
		} else {
			imagejpeg($image, $destination_url, 96);
		}*/
		if($image) {
			return imagejpeg($image, $destination_url, 50);
			//return $destination_url;
		} else {
			return false;
		}
	}
}