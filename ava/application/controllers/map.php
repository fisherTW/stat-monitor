<?php
/*
* File:			map.php
* Version:		-
* Last changed:	2015/02/12
* Purpose:		-
* Author:		Fisher Liao / fisher.liao@gmail.com
* Copyright:	(C) 2015
* Product:		AVA
*/
class Map extends CI_Controller {
	public function __construct() {
		parent::__construct();
		//$this->lang->load("message","english");
		$this->load->helper('url');
		$this->load->helper('language');
	}

	public function index() {
		$this->load->model('city_model');
		$this->load->model('status_model');

		$data['title'] = '登入';
		$data['func'] = 'map';
		$data['no_nav'] = 1;

		$data['ourl'] = str_replace('ava','php_openid',base_url()).'test_openid.php';

		$data['ary_citySetting'] = $this->city_model->getCitySetting();
		$data['ary_city'] = $this->city_model->getCity();

		$data['json_light'] = json_encode($this->status_model->getLight());

		$this->load->view('map/index',$data);
	}
}