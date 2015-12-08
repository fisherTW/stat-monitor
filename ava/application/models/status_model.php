<?php
/*
* File:			status_model.php
* Version:		-
* Last changed:	2015/02/12
* Purpose:		-
* Author:		Fisher Liao / fisher.liao@gmail.com
* Copyright:	(C) 2015
* Product:		-
*/
class Status_model extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->load->database();
	}

	public function getStatus($city_id = FALSE) {
		$this->load->model('log_model');
		$tbName = $this->log_model->getNowTableName();

		$ary_cou = array();

		if($city_id) {
			$where = 'city_id = '.$city_id.' AND mem is not NULL';
			$this->db->where($where);
			$this->db->order_by("id", "desc");
			$this->db->limit(1);
			$query = $this->db->get($tbName);
			$ary_ret = $query->result_array();

			for($i=0; $i < count($ary_ret); $i++) {
				$ary_cou = $ary_ret[$i];
			}

			return $ary_cou;
		} else {
			$str = '';
			$ary_cou = array();
			$this->load->model('city_model');
			$ary_citySetting = $this->city_model->getCitySetting(true);

			foreach($ary_citySetting as $key => $val) {
			$str .= (strlen($str) == 0 ? '' : ' union')
				.'(SELECT * 
				FROM '.$tbName
				.' WHERE city_id = '. $key
				.'   and mem is not NULL'
				.' ORDER BY id DESC LIMIT 1) ';                
			}
			$query = $this->db->query($str);
			$ary_ret = $query->result_array();

			for($i=0; $i < count($ary_ret); $i++) {
				$ary_cou[$ary_ret[$i]['city_id']] = $ary_ret[$i];
			}
			return $ary_cou;
		}
	}

	public function getLight($is_for_send = FALSE) {
		$ary_cou = array();
		$ary_is_for_send = array();

		if($is_for_send) {
			$this->db->where('count_red', 3);
		}
		$query = $this->db->get('light');
		$ary_ret = $query->result_array();

		for($i=0; $i < count($ary_ret); $i++) {
			$ary_cou[$ary_ret[$i]['city_id']] = $ary_ret[$i]['light'];
			$ary_is_for_send[$ary_ret[$i]['city_id']] = $ary_ret[$i];
		}

		if($is_for_send) {
			$ary_cou = $ary_is_for_send;
		}

		return $ary_cou;
	}
}