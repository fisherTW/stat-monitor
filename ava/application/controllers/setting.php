<?php
/*
* File:			setting.php
* Version:		-
* Last changed:	2015/02/12
* Purpose:		-
* Author:		Fisher Liao / fisher.liao@gmail.com
* Copyright:	(C) 2015
* Product:		-
*/
class Setting extends CI_Controller {
	public function __construct() {
		parent::__construct();
		//$this->lang->load("message","english");
		$this->load->helper('url');
		$this->load->helper('language');

		$this->load->model('city_model');
		$this->ary_city = $this->city_model->getCity();
	}

	public function index() {
		if($this->session->userdata('u_s') === FALSE) show_error('',401);

		$data['title'] = '設定';
		$data['func'] = 'setting';

		$data['u_s'] = json_decode($this->session->userdata('u_s'));

		$city_id = $data['u_s']->city_id;

//print_r($data['u_s']);

		$data['ary_city'] = $this->ary_city;

		$data['ary_thisCitySetting'] = $this->city_model->getSingleCitySetting($city_id);

		$this->load->view('templates/header',$data);
		$this->load->view('setting/index',$data);
		$this->load->view('templates/footer',$data);
	}

	public function submit() {
		echo $this->city_model->updateCitySetting();
	}

	public function backDefault() {
		echo $this->city_model->defaultCitySetting();	
	}
}