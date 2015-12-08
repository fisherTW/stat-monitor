<?php
/*
* File:			user_model.php
* Version:		-
* Last changed:	2015/02/12
* Purpose:		-
* Author:		Fisher Liao / fisher.liao@gmail.com
* Copyright:	(C) 2015
* Product:		-
*/
class User_model extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->load->database();
	}

	public function getUser($mail) {
		$ary_ret = array();

		$where = array(
			'mail'	=> $mail,
			'is_enable' => true
		);
		$this->db->where($where);
		$query = $this->db->get('user');
		$ary_ret = $query->result_array();

		return $ary_ret;
	}
}