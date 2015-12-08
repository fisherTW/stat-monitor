<?php
/*
* File:			city_model.php
* Version:		-
* Last changed:	2015/02/12
* Purpose:		-
* Author:		Fisher Liao / fisher.liao@gmail.com
* Copyright:	(C) 2015
* Product:		-
*/
class City_model extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->load->database();
	}

	public function getCity() {
		$ary_cou = array();

		$query = $this->db->get('city');
		$ary_ret = $query->result_array();

		for($i=0; $i < count($ary_ret); $i++) {
			$ary_cou[$ary_ret[$i]['id']] = $ary_ret[$i]['tw'];
		}

		return $ary_cou;
	}

	public function getCitySetting($isEnabledOnly = false) {
		$ary_cou = array();

		if($isEnabledOnly) $this->db->where('enabled', 1);
		$query = $this->db->get('setting_city');
		$ary_ret = $query->result_array();

		for($i=0; $i < count($ary_ret); $i++) {
			$ary_cou[$ary_ret[$i]['city_id']] = $ary_ret[$i];
		}

		return $ary_cou;
	}

	public function getSingleCitySetting($city_id) {
		$ary_cou = array();

		$this->db->where('city_id', $city_id);
		$query = $this->db->get('setting_city');
		$ary_ret = $query->result_array();

		for($i=0; $i < count($ary_ret); $i++) {
			$ary_cou = $ary_ret[$i];
		}

		return $ary_cou;
	}

	public function updateCitySetting() {
		$data = array(
			'contact_1' => $this->input->post('contact_1'),
			'contact_2' => $this->input->post('contact_2'),
			'contact_name_1' => $this->input->post('contact_name_1'),
			'contact_name_2' => $this->input->post('contact_name_2'),
			'send_mail' => $this->input->post('send_mail'),
			'send_sms_1' => $this->input->post('send_sms_1'),
			'send_sms_2' => $this->input->post('send_sms_2'),
			'thr_icmp' => $this->input->post('txt_icmp'),
			'thr_cpu' => $this->input->post('txt_cpu'),
			'thr_mem' => $this->input->post('txt_mem')
		);

		$this->db->where('city_id', $this->input->post('hid_cityId'));
		$this->db->update('setting_city', $data);

		return 1;
	}

	public function defaultCitySetting() {
		$this->db->set('thr_icmp', 'default_thr_icmp', FALSE);
		$this->db->set('thr_cpu', 'default_thr_cpu', FALSE);
		$this->db->set('thr_mem', 'default_thr_mem', FALSE);
		$this->db->where('city_id', $this->input->post('hid_cityId'));
		$this->db->update('setting_city');

		return 1;		
	}
}