<?php
/*
* File:			detail.php
* Version:		-
* Last changed:	2015/02/12
* Purpose:		-
* Author:		Fisher Liao / fisher.liao@gmail.com
* Copyright:	(C) 2015
* Product:		-
*/
class Detail extends CI_Controller {
	public function __construct() {
		parent::__construct();
		//$this->lang->load("message","english");
		$this->load->helper('url');
		$this->load->helper('language');

		$this->load->model('city_model');
		$this->load->model('status_model');
		$this->ary_city = $this->city_model->getCity();
		$this->ary_citySetting = $this->city_model->getCitySetting();
	}

	public function index($city_id = 1) {
		if($this->session->userdata('u_s') === FALSE) show_error('',401);
		
		$data['title'] = '詳細資料';
		$data['func'] = 'detail';

		$data['u_s'] = json_decode($this->session->userdata('u_s'));

		$data['city_id'] = $city_id;
		$data['ary_city'] = $this->ary_city;
		$data['ary_citySetting'] = $this->ary_citySetting;
		$data['ary_status'] = $this->status_model->getStatus($city_id);
		$data['lightHtml'] = $this->getLightHtml($data['ary_citySetting'], $data['ary_status']);

		$data['json_light'] = json_encode($this->status_model->getLight());

		$this->load->view('templates/header',$data);
		$this->load->view('detail/index',$data);
		$this->load->view('templates/footer',$data);
	}

	public function getLightHtml($ary_citySetting, $ary_status) {
		$ary_s = $ary_citySetting[$ary_status['city_id']];

		$html_icmp = (($ary_status['icmp'] > $ary_s['thr_icmp']) ? $this->getLightDiv('alert', $ary_status['icmp'].'%') : $this->getLightDiv('ok', $ary_status['icmp'].'%'));
		$html_web_service = (($ary_status['web_service'] != 1) ? $this->getLightDiv('alert', 'down') : $this->getLightDiv('ok', 'up'));
		if( ($ary_status['cpu'] == -1) || (is_null($ary_status['cpu'])) ) {
			$html_cpu = $this->getLightDiv('warn', '未取得資料');
		} else {
			$html_cpu = (($ary_status['cpu'] > $ary_s['thr_cpu']) ? $this->getLightDiv('alert', $ary_status['cpu'].'%') : $this->getLightDiv('ok', $ary_status['cpu'].'%'));
		}
		if( ($ary_status['mem'] == -1) || (is_null($ary_status['mem'])) ) {
			$html_mem = $this->getLightDiv('warn', '未取得資料');
		} else {
			$html_mem = (($ary_status['mem'] > $ary_s['thr_mem']) ? $this->getLightDiv('alert', $ary_status['mem'].'%') : $this->getLightDiv('ok', $ary_status['mem'].'%'));
		}
		if( ($ary_status['sess'] == -1) || (is_null($ary_status['sess'])) ) {
			$html_sess = $this->getLightDiv('warn', '未取得資料');
		} else {
			$html_sess = $this->getLightDiv('ok', $ary_status['sess']);
		}

		return array($html_icmp, $html_web_service, $html_cpu, $html_mem, $html_sess);
	}

	// $type: ok, warn, alert
	public function getLightDiv($type, $txt) {
		switch($type) {
			case 'ok':
				$class_div = 'alert-success';
				$class_span = 'glyphicon-ok-sign';break;
			case 'warn':
				$class_div = 'alert-warning';
				$class_span = 'glyphicon-question-sign';break;
			case 'alert':
				$class_div = 'alert-danger';
				$class_span = 'glyphicon-exclamation-sign';break;
		}

		$str = "
			<div class='col-md-4 alert $class_div' role='alert'>
				<span class='glyphicon $class_span' aria-hidden='true'></span>
				$txt
			</div>";

		return $str;
	}
}