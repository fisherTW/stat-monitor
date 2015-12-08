<?php
/*
* File:			history.php
* Version:		-
* Last changed:	2015/02/12
* Purpose:		-
* Author:		Fisher Liao / fisher.liao@gmail.com
* Copyright:	(C) 2015
* Product:		AVA
*/
class History extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->helper('url');
	}

	public function index() {
		if($this->session->userdata('u_s') === FALSE) show_error('',401);

		$this->load->model('city_model');
		$this->load->model('status_model');

		$data['u_s'] = json_decode($this->session->userdata('u_s'));

		$data['ary_citySetting'] = $this->city_model->getCitySetting();
		$data['ary_city'] = $this->city_model->getCity();
		$data['title'] = '歷史查詢';

		$this->load->view('templates/header',$data);
		$this->load->view('history/index',$data);
		$this->load->view('templates/footer',$data);
	}

	public function getTable() {
		$this->load->model('log_model');
		echo $this->log_model->getTable();
	}
}