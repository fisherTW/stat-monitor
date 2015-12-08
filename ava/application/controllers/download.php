<?php
/*
* File:			download.php
* Version:		-
* Last changed:	2015/02/12
* Purpose:		-
* Author:		Fisher Liao / fisher.liao@gmail.com
* Copyright:	(C) 2015
* Product:		AVA
*/
class Download extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->helper('download');
		$this->load->helper('url');
	}

	public function index() {
		if($this->session->userdata('u_s') === FALSE) show_error('',401);

		$data = file_get_contents(base_url().'/assets/file/file.zip');
		$name = 'file.zip';

		force_download($name, $data);
	}
}